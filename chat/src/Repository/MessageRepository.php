<?php

namespace Lentech\Botster\Repository;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Entity\MessageEntity;

class MessageRepository
{
	const TABLE = 'messages';

	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh, QueryFactory $query_factory)
	{
		$this->dbh = $dbh;
		$this->query_factory = $query_factory;
	}

	/**
	 * Creates a new message.
	 */
	public function create(MessageEntity $message)
	{
		$insert = $this->query_factory->newInsert()
			->into(self::TABLE)
			->cols(['conversation_id', 'author_id', 'text', 'time'])
			->bindValues([
				'conversation_id' => $message->conversation_id,
				'author_id' => $message->author_id,
				'text' => $message->text,
				'time' => time(),
			]);

		$query = $this->dbh->prepare($insert->__toString());

		return $query->execute($insert->getBindValues());
	}

	/**
	 * Gets the latest message in the specified conversation with an optional
	 * offset.
	 *
	 * @param int $conversation_id
	 * @param int $offset
	 * @return MessageEntity|false Message or false on failure
	 */
	public function getInConversation($conversation_id, $offset = 0)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('conversation_id = :conversation_id')
			->orderBy(['id DESC'])
			->limit(1)
			->offset($offset)
			->bindValue('conversation_id', $conversation_id);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new MessageEntity($data);
		}

		return false;
	}

	/**
	 * Gets the latest message in the specified conversation and by the
	 * specified author with an optional offset.
	 *
	 * @param int $conversation_id
	 * @param int $author_id
	 * @param int $offset
	 * @return MessageEntity|false Message or false on failure
	 */
	public function getInConversationByAuthor($conversation_id, $author_id, $offset = 0)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('conversation_id = :conversation_id')
			->where('author_id = :author_id')
			->orderBy(['id DESC'])
			->limit(1)
			->offset($offset)
			->bindValues([
				'conversation_id' => $conversation_id,
				'author_id' => $author_id,
			]);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new MessageEntity($data);
		}

		return false;
	}

	/**
	 * Gets the latest messages in the conversation with an optional limit.
	 *
	 * @param int $conversation_id
	 * @param int $amount
	 * @return array Message entities
	 */
	public function getLatestInConversation($conversation_id, $amount = null)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('conversation_id = :conversation_id')
			->orderBy(['id DESC'])
			->bindValue('conversation_id', $conversation_id);

		if ($amount !== null)
		{
			$select->limit($amount);
		}

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		$conversations = [];

		while ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			$conversations[] = new MessageEntity($data);
		}

		return $conversations;
	}

	public function getInConversationAfterMessage($conversation_id, $message_id)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('conversation_id = :conversation_id')
			->where('id > :id')
			->bindValues([
				'conversation_id' => $conversation_id,
				'id' => $message_id,
			]);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		$conversations = [];

		while ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			$conversations[] = new MessageEntity($data);
		}

		return $conversations;
	}
}