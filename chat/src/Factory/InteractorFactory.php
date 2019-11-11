<?php

namespace Lentech\Botster\Factory;

use Lentech\Botster\Interactor\StartConversationInteractor;
use Lentech\Botster\Interactor\SayMessageInteractor;
use Lentech\Botster\Interactor\LetRespondInteractor;

class InteractorFactory
{
	private $dbh;

	public function __construct(\PDO $dbh)
	{
		$this->dbh = $dbh;
	}

	public function makeStartConversation()
	{
		$repository_factory = new RepositoryFactory($this->dbh);

		return new StartConversationInteractor(
			$repository_factory->makeConversation()
		);
	}

	public function makeSayMessage()
	{
		$repository_factory = new RepositoryFactory($this->dbh);

		return new SayMessageInteractor(
			$repository_factory->makeMessage()
		);
	}

	public function makeLetRespond()
	{
		$repository_factory = new RepositoryFactory($this->dbh);

		return new LetRespondInteractor(
			$repository_factory->makeUtterance(),
			$repository_factory->makeConnection(),
			$repository_factory->makeWord(),
			$repository_factory->makeMessage(),
			$repository_factory->makeLog()
		);
	}
}