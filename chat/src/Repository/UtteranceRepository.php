<?php

namespace Lentech\Botster\Repository;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Entity\UtteranceEntity;

class UtteranceRepository
{
	const TABLE = 'utterances';

	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh, QueryFactory $query_factory)
	{
		$this->dbh = $dbh;
		$this->query_factory = $query_factory;
	}

	/**
	 * Creates an utterance.
	 *
	 * @return bool Success
	 */
	public function create(UtteranceEntity $utterance)
	{
		$insert = $this->query_factory->newInsert()
			->into(self::TABLE)
			->cols(['text'])
			->bindValues([
				'text' => $utterance->text,
			]);

		$query = $this->dbh->prepare($insert->__toString());

		return $query->execute($insert->getBindValues());
	}

	/**
	 * Saves a modified utterance.
	 *
	 * @return bool Success
	 */
	public function save(UtteranceEntity $utterance)
	{
		$update = $this->query_factory->newUpdate()
			->table(self::TABLE)
			->cols(['said'])
			->where('id = :id')
			->limit(1)
			->bindValues([
				':id' => $utterance->id,
				':said' => $utterance->said,
			]);

		$query = $this->dbh->prepare($update->__toString());

		return $query->execute($update->getBindValues());
	}

	/**
	 * Gets an utterance with the specified ID.
	 *
	 * @param int $id The utterance ID
	 * @return UtteranceEntity|false Utterance or false on failure
	 */
	public function getWithId($id)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('id = :id')
			->limit(1)
			->bindValue('id', $id);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if (($data = $query->fetch(\PDO::FETCH_ASSOC)))
		{
			return new UtteranceEntity($data);
		}

		return false;
	}

	/**
	 * Gets an utterance with the specified text.
	 *
	 * @param string $text The text
	 * @return UtteranceEntity|false Utterance or false on failure
	 */
	public function getWithText($text)
	{
		$select = $this->query_factory->newSelect()
			->cols(['*'])
			->from(self::TABLE)
			->where('text = :text')
			->limit(1)
			->bindValue('text', $text);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new UtteranceEntity($data);
		}

		return false;
	}

	/**
	 * Gets an utterance with the most similar text.
	 *
	 * @param string $text
	 * @return UtteranceEntity|false Utterance or false on failure
	 */
	public function getWithSimilarText($text)
	{
		$select = $this->query_factory->newSelect()
			->cols([
				'u.id',
				'u.text',
				'MATCH (u.text) AGAINST (:text) AS relevance',
				'count(c.from) AS connections',
			])
			->from(self::TABLE.' AS u')
			->join('', 'connections AS c', 'u.id = c.from')
			->where('MATCH (u.text) AGAINST (:text)')
			->groupBy(['u.id'])
			->orderBy([
				'relevance DESC',
				'u.said DESC',
				'connections DESC',
			])
			->limit(1)
			->bindValue('text', $text);

		$query = $this->dbh->prepare($select->__toString());
		$query->execute($select->getBindValues());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new UtteranceEntity($data);
		}

		return false;
	}

	/**
	 * Gets the best utterance to be learnt. This is calculated by selecting an
	 * utterance with the least amount of connections at random.
	 *
	 * @return UtteranceEntity|false Utterance or false on failure
	 */
	public function getBestToLearn()
	{
		$select = $this->query_factory->newSelect()
			->cols([
				'u.*',
				'(SELECT COUNT(*) FROM connections AS c WHERE c.from = u.id) AS num_connections',
			])
			->from(self::TABLE.' AS u')
			->orderBy([
				'num_connections DESC',
				'u.said DESC',
			])
			->limit(50);

		$select2 = $this->query_factory->newSelect()
			->cols(['*'])
			->fromSubSelect($select, 't')
			->orderBy(['RAND()'])
			->limit(1);

		$query = $this->dbh->query($select2->__toString());

		if ($data = $query->fetch(\PDO::FETCH_ASSOC))
		{
			return new UtteranceEntity($data);
		}

		return false;
	}

	/**
	 * Counts the number of utterances.
	 *
	 * @return int Number of utterances
	 */
	public function count()
	{
		$select = $this->query_factory->newSelect()
			->cols(['COUNT(*)'])
			->from(self::TABLE);

		return $this->dbh->query($select->__toString())->fetchColumn();
	}
}