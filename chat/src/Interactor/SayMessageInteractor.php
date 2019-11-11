<?php

namespace Lentech\Botster\Interactor;

use Lentech\Botster\Repository\MessageRepository;
use Lentech\Botster\Entity\MessageEntity;

class SayMessageInteractor
{
	private $message_repository;

	public function __construct(MessageRepository $message_repository)
	{
		$this->message_repository = $message_repository;
	}

	/**
	 * Say message in conversation as the user.
	 *
	 * @param int $conversation_id
	 * @param string $message
	 * @return bool Success
	 */
	public function interact($conversation_id, $message)
	{
		$message_entity = new MessageEntity([
			'conversation_id' => $conversation_id,
			'author_id' => MessageEntity::USER,
			'text' => $message,
		]);

		return $this->message_repository->create($message_entity);
	}
}