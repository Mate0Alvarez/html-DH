<?php

namespace Lentech\Botster\Repository;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Entity\WordEntity;

class WordRepository
{
	const TABLE = 'words';

	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh, QueryFactory $query_factory)
	{
		$this->dbh = $dbh;
		$this->query_factory = $query_factory;
	}

	/**
	 * Creates a new word.
	 *
	 * @return bool Success
	 */
	public function create(WordEntity $word)
	{
		$insert = $this->query_factory->newInsert()
			->into(self::TABLE)
			->cols(['text', 'definition'])
			->bindValues([
				':text' => $word->text,
				':definition' => $word->definition,
			]);

		$query = $this->dbh->prepare($insert->__toString());

		return $query->execute($insert->getBindValues());
	}

	/**
	 * Gets a word.
	 *
	 * @param string $text
	 * @return WordEntity|false Word or false on failure
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
			return new WordEntity($data);
		}

		return false;
	}
}