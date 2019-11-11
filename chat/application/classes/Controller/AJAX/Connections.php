<?php

use Lentech\Botster\Factory\RepositoryFactory;

class Controller_AJAX_Connections extends Controller
{
	public function action_index()
	{
        //Connect to database
        $dbh = db_connect();

        // Make connection repository
        $connection_repository = (new RepositoryFactory($dbh))->makeConnection();

        $this->response->body(number_format($connection_repository->count()));
    }
}
