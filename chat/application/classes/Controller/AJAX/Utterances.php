<?php

use Lentech\Botster\Factory\RepositoryFactory;

class Controller_AJAX_Utterances extends Controller
{
	public function action_index()
	{
        // Connect to database
        $dbh = db_connect();

        // Make utterance repository
        $utterance_repository = (new RepositoryFactory($dbh))->makeUtterance();

        $this->response->body(number_format($utterance_repository->count()));
    }
}
