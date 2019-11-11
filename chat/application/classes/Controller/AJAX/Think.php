<?php

use Lentech\Botster\Factory\InteractorFactory;

class Controller_AJAX_Think extends Controller
{
	public function action_index()
	{
        //Connect to database
        $dbh = db_connect();

        // Continue session
        session_start();

        //Check if conversation ID is set
        if(!isset($_SESSION['conversation_id']))
        {
            exit("Error: Conversation ID is not set.");
        }

        // Build interactor
        $let_respond_interactor = (new InteractorFactory($dbh))->makeLetRespond();

        // Let Botster respond in conversation
        $let_respond_interactor->interact($_SESSION['conversation_id']);
    }
}
