<?php

namespace Lentech\Botster\Factory;

use Aura\SqlQuery\QueryFactory;
use Lentech\Botster\Repository\UtteranceRepository;
use Lentech\Botster\Repository\ConnectionRepository;
use Lentech\Botster\Repository\ConversationRepository;
use Lentech\Botster\Repository\WordRepository;
use Lentech\Botster\Repository\MessageRepository;
use Lentech\Botster\Repository\LogRepository;

class RepositoryFactory
{
	private $dbh;
	private $query_factory;

	public function __construct(\PDO $dbh)
	{
		$this->dbh = $dbh;
		$this->query_factory = new QueryFactory('mysql');
	}

	public function makeUtterance()
	{
		return new UtteranceRepository($this->dbh, $this->query_factory);
	}

	public function makeConnection()
	{
		return new ConnectionRepository($this->dbh, $this->query_factory);
	}

	public function makeConversation()
	{
		return new ConversationRepository($this->dbh, $this->query_factory);
	}

	public function makeWord()
	{
		return new WordRepository($this->dbh, $this->query_factory);
	}

	public function makeMessage()
	{
		return new MessageRepository($this->dbh, $this->query_factory);
	}

	public function makeLog()
	{
		return new LogRepository($this->dbh, $this->query_factory);
	}
}