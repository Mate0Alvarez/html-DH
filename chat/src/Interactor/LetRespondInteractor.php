<?php

namespace Lentech\Botster\Interactor;

use Lentech\Botster\Repository\UtteranceRepository;
use Lentech\Botster\Repository\ConnectionRepository;
use Lentech\Botster\Repository\WordRepository;
use Lentech\Botster\Repository\MessageRepository;
use Lentech\Botster\Repository\LogRepository;
use Lentech\Botster\Entity\MessageEntity;
use Lentech\Botster\Entity\LogEntity;
use Lentech\Botster\Entity\ConnectionEntity;
use Lentech\Botster\Entity\UtteranceEntity;

class LetRespondInteractor
{
	private $utterance_repository;
	private $connection_repository;
	private $word_repository;
	private $message_repository;
	private $log_repository;

	public function __construct(
		UtteranceRepository $utterance_repository,
		ConnectionRepository $connection_repository,
		WordRepository $word_repository,
		MessageRepository $message_repository,
		LogRepository $log_repository
	) {
		$this->utterance_repository = $utterance_repository;
		$this->connection_repository = $connection_repository;
		$this->word_repository = $word_repository;
		$this->message_repository = $message_repository;
		$this->log_repository = $log_repository;
	}

	/**
	 * Lets the bot respond in a conversation if it so chooses.
	 *
	 * @param int $conversation_id Conversation ID
	 * @return bool Whether a response was made in the conversation
	 */
	public function interact($conversation_id)
	{
		// Get starting time of execution
		$execution_start = microtime(true);

		// Get last message
		$input = $this->message_repository->getInConversation($conversation_id);

		// Return false if message doesn't exist or isn't the user's
		if ($input === false || $input->author_id != 1)
			return false;

		// Create log entity
		$log = new LogEntity();

		// Log conversation ID
		$log->append('Conversation ID: '.$conversation_id);

		// Log input
		$log->append('Input: '.$input->text);

		// Get previous input
		$previous_input = $this->message_repository->getInConversationByAuthor($conversation_id, MessageEntity::USER, 1);

		// Get whether input has been said before
		$input_said_before = ($previous_input !== false && $input->text === $previous_input->text);

		// If input not said before and sentence isn't spam
		if (! $input_said_before && ! $this->checkSpam($input->text, $log))
		{
            $utterance = $this->utterance_repository->getWithText($input->text);

			if (! $utterance) {
                $this->learnUtterance($input->text);
				$log->append('Learned the new utterance "'.$input->text.'".');
			} else {
                $this->incrementUtteranceSaidCount($utterance);
				$log->append('Incemented utterance "'.$input->text.'" said value by 1.');
			}

			// Get previous output
			$previous_output = $this->message_repository->getInConversationByAuthor($conversation_id, MessageEntity::BOT);

			// If previous output exists
			if ($previous_output !== false)
			{
				// Strengthen connection
				$this->strengthenConnection($previous_output->text, $input->text);

				$log->append('Strengthened connection between "'.$previous_output->text.'" and "'.$input->text.'".');
			}
		}

		// Get responses to exclude from brain search
		$excluded_responses = [$input->text];

		// Get previous output
		$previous_output = $this->message_repository->getInConversationByAuthor($conversation_id, MessageEntity::BOT);

		// If previous output exists
		if ($previous_output !== false)
		{
			// Add previous output to excluded responses
			$excluded_responses[] = $previous_output->text;
		}

		// Do strict search
		if ($this->connection_repository->getFrom($input->text, 1) &&
			($output = $this->getResponse($input->text, $excluded_responses)) !== false)
		{
			$log->append('Output type: Strict search');
		}
		// Do light search
		elseif (($similar_utterance = $this->utterance_repository->getWithSimilarText($input->text)) &&
			($output = $this->getResponse($similar_utterance->text, $excluded_responses)) !== false)
		{
			$log->append('Found similar input from light search: '.$similar_utterance->text);
			$log->append('Output type: Light search');
		}
		// Default to fail search
		else
		{
			$log->append('Output type: Failed search');
			$output = $this->utterance_repository->getBestToLearn()->text;
		}

		// If output is in the old format
		if (! preg_match('/[a-z]/', $output))
		{
			// Beautify output
			$output = $this->beautifyInput($output);
		}

		// Log output
		$log->append('Output: '.$output);

		// Log execution time
		$execution_finish = microtime(true);
		$execution_time = $execution_finish - $execution_start;
		$log->append('Script executed in '.$execution_time.' seconds.');

		// Say message in conversation
		$message = new MessageEntity([
			'conversation_id' => $conversation_id,
			'author_id' => MessageEntity::BOT,
			'text' => $output,
		]);
		$this->message_repository->create($message);

		// Save log
		$this->log_repository->create($log);

		return true;
	}

	/**
	 * Strengthens the connection between an input and output.
	 *
	 * @param string $input
	 * @param string $output
	 * @return bool Success
	 */
	private function strengthenConnection($input, $output)
	{
		// Get connection
		$connection = $this->connection_repository->getBetween($input, $output);

		// If this connection already exists
		if ($connection)
		{
			// Increment connection strength
			$connection->strength++;

			// Save connection
			return $this->connection_repository->save($connection);
		}
		else
		{
			// Get utterance IDs
			$input = $this->utterance_repository->getWithText($input);
			$output = $this->utterance_repository->getWithText($output);

			// If input or output doesn't exist
			if (! $input || ! $output)
			{
				return false;
			}

			// Create new connection
			$connection = new ConnectionEntity([
				'from' => $input->id,
				'to' => $output->id,
			]);

			// Add connection to database
			return $this->connection_repository->create($connection);
		}
	}

	/**
	 * Searches the brain for an output in response to an input
	 *
	 * @param string $input
	 * @param $excluded_responses Array of responses to exclude from the search
	 * @return string|false Output text or false when an output is not found
	 */
	private function getResponse($input, array $excluded_responses = null)
	{
		// Get responses
		$connections = $this->connection_repository->getResponses($input, 10, $excluded_responses);

		// If atleast one connection was found
		if (! empty($connections))
		{
			// Get strengths array
			$strengths = [];

			foreach ($connections as $connection)
			{
				$strengths[] = $connection->strength;
			}

			// Return random connection based on strength
			$connection = $this->getRandomWeightedValue($connections, $strengths);

			// Return the output as a string
			return $this->utterance_repository->getWithId($connection->to)->text;
		}

		return false;
	}

	/**
	 * Picks a random item based on weights.
	 *
	 * @param $values Array of elements to choose from
	 * @param $weights An array of weights. Weight must be a positive number
	 * @return mixed Selected element
	*/
    private function getRandomWeightedValue(array $values, array $weights)
	{
		$count = count($values);
		$i = 0;
		$n = 0;
		$num = mt_rand(0, array_sum($weights));

		while ($i < $count)
		{
			$n += $weights[$i];

			if ($n >= $num)
			{
				break;
			}

			$i++;
		}

		return $values[$i];
    }

	/**
	 * Splits a sentence into words.
	 *
	 * @param string $filtered_sentence
	 * @return array Words
	 */
	private function splitSentenceIntoWords($filtered_sentence)
	{
		// Get words
		preg_match_all('/\b[a-z\-\'0-9\.]+\b/i', $filtered_sentence, $words);

		// Split sentence into words
		return $words[0];
	}

	/**
	 * Checks whether an input is spam
	 *
	 * @param string $input
	 * @return bool
	 */
	private function checkSpam($input, LogEntity $log)
	{
		// Get words
		$words = $this->splitSentenceIntoWords($input);

		//Check for spam words
		foreach($words as $word)
		{
			// If word doesn't exist
			if (! $this->word_repository->getWithText($word))
			{
				$log->append('Found "'.$word.'" to be spam.');
				return true;
			}
		}

		return false;
	}

	/**
	 * Attempts to fix any cosmetic errors in an input.
	 *
	 * @param string $sentence
	 * @return string Beautified sentence
	 */
	private function beautifyInput($sentence)
	{
		//Fix the case
		$sentence = ucfirst(strtolower($sentence));
		$sentence = preg_replace("# i( |$)#", " I$1", $sentence);
		$sentence = preg_replace("# i'#", " I'", $sentence);

		//Add fullstop if needed
		if(!in_array(substr($sentence, -1), array('.', '?', '!')))
		{
			$sentence .= ".";
		}

		return $sentence;
	}

    /**
	 * Learns a new utterance.
	 *
	 * @param string $utterance_text
	 * @return void
	 */
	private function learnUtterance($utterance_text)
	{
		$utterance = new UtteranceEntity([
			'text' => $utterance_text,
		]);

		$this->utterance_repository->create($utterance);
	}

	/**
	 * Increments an utterance's said count by 1.
	 *
	 * @return void
	 */
	private function incrementUtteranceSaidCount(UtteranceEntity $utterance)
	{
		$utterance->said++;
		$this->utterance_repository->save($utterance);
	}
}
