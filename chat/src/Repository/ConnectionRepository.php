<?php

namespace Lentech\Botster\Repository;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Entity\ConnectionEntity;

class ConnectionRepository
{
	const TABLE = 'connections';

	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh, QueryFactory $query_factory)
	{
		$this->dbh = $dbh;
		$this->query_factory = $query_factory;
	}

	/**
	 * Creates a new connection.
	 *
	 * @return bool Success
	 */
	public function create(ConnectionEntity $connection)
	{
		$insert = $this->query_factory->newInsert()
			->into(self::TABLE)
			->cols(['from', 'to'])
			->bindValues([
				'from' => $connection->from,
				'to' => $connection->to,
			]);

		$query = $this->dbh->prepare($insert->__toString());

		return $query->execute($insert->getBindValues());
	}

	/**
	 * Updates a connection with the modified data.
	 *
	 * @return bool Successful
	 */
	public function save(ConnectionEntity $connection)
	{
		$update = $this->query_factory->newUpdate()
			->table(self::TABLE)
			->cols(['strength'])
			->where('id = :id')
			->limit(1)
			->bindValues([
				'id' => $connection->id,
				'strength' => $connection->strength,
			]);

		$query = $this->dbh->prepare($update->__toString());

		return $query->execute($update->getBindValues());
	}

	/**
	 * Gets a connection which is from the specified utterance.
	 *
	 * @param string $utterance_text Utterance text
	 * @param int $amount Amount to return
	 * @return ConnectionEntity|false Connection or false on failure
	 */
	public function getFrom($utterance_text, $amount = null)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('`from` = (
				SELECT id
				FROM utterances
				WHERE text = :text
			)')
			->bindValue('text', $utterance_text);

		if ($amount !== null)
		{
			$select->limit($amount);
		}

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new ConnectionEntity($data);
		}

		return false;
	}

	/**
	 * Gets the connection between two specified utterances.
	 *
	 * @param string $from_utterance
	 * @param string $to_utterance
	 * @return ConnectionEntity Connection
	 */
	public function getBetween($from_utterance, $to_utterance)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('`from` = (
				SELECT id
				FROM utterances
				WHERE text = :from
			)')
			->where('`to` = (
				SELECT id
				FROM utterances
				WHERE text = :to
			)')
			->limit(1)
			->bindValues([
				'from' => $from_utterance,
				'to' => $to_utterance,
			]);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new ConnectionEntity($data);
		}

		return false;
	}

	/**
	 * Gets the best outputs in response to an input.
	 *
	 * @param string $input The input text
	 * @param int $amount Amount of responses to return
	 * @param $excluded_responses Responses to not return
	 * @return array Connections
	 */
	public function getResponses($input, $amount = null, array $excluded_responses = null)
	{
		// Generate excluded outputs SQL snippit
		foreach ((array) $excluded_responses as $key => $output)
		{
			$excluded_responses[$key] = $this->dbh->quote($output);
		}
		$excluded_responses_sql = implode(', ', $excluded_responses);

		$select = $this->query_factory->newSelect()
			->cols(['c.*'])
			->from(self::TABLE.' AS c')
			->join('LEFT', 'utterances AS i', 'i.id = c.from')
			->join('LEFT', 'utterances AS o', 'o.id = c.to')
			->where('i.text = :input')
			->where('o.text NOT IN ('.$excluded_responses_sql.')')
			->orderBy(['strength DESC'])
			->bindValue('input', $input);

		if ($amount !== null)
		{
			$select->limit($amount);
		}

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		$connections = [];

		while ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			$connections[] = new ConnectionEntity($data);
		}

		return $connections;
	}

	/**
	 * Counts the number of connections.
	 *
	 * @return int Number of connections
	 */
	public function count()
	{
		$select = $this->query_factory->newSelect()
			->cols(['COUNT(*)'])
			->from('connections');

		return $this->dbh->query($select->__toString())->fetchColumn();
	}
}