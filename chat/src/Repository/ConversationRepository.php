<?php

namespace Lentech\Botster\Repository;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Entity\ConversationEntity;

class ConversationRepository
{
	const TABLE = 'conversations';

	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh, QueryFactory $query_factory)
	{
		$this->dbh = $dbh;
		$this->query_factory = $query_factory;
	}

	/**
	 * Creates a new conversation.
	 *
	 * @return bool
	 */
	public function create(ConversationEntity $conversation)
	{
		$insert = $this->query_factory->newInsert()
			->into(self::TABLE)
			->cols(['ip', 'user_agent'])
			->bindValues([
				'ip' => $conversation->ip,
				'user_agent' => $conversation->user_agent,
			]);

		$query = $this->dbh->prepare($insert->__toString());

		$success = $query->execute($insert->getBindValues());

		// Set conversation ID
		$conversation->id = $this->dbh->lastInsertId();

		return $success;
	}

	/**
	 * Counts the number of conversations.
	 *
	 * @return int Number of conversations
	 */
	public function count()
	{
		$select = $this->query_factory->newSelect()
			->cols(['COUNT(*)'])
			->from(self::TABLE);

		return $this->dbh->query($select->__toString())->fetchColumn();
	}
}