<?php

namespace Lentech\Botster\Repository;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Entity\LogEntity;

class LogRepository
{
	const TABLE = 'logs';

	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh, QueryFactory $query_factory)
	{
		$this->dbh = $dbh;
		$this->query_factory = $query_factory;
	}

	/**
	 * Inserts the log into the database.
	 *
	 * @return bool Successful
	 */
	public function create(LogEntity $log)
	{
		$insert = $this->query_factory->newInsert()
			->into(self::TABLE)
			->cols(['log'])
			->bindValues([
				'log' => $log->log,
			]);

		$query = $this->dbh->prepare($insert->__toString());

		return $query->execute($insert->getBindValues());
	}
}