<?php

use Lentech\Botster\Factory\RepositoryFactory;
use Lentech\Botster\Factory\InteractorFactory;

class Controller_AJAX_Conversations extends Controller
{
	public function action_index()
	{
        //Connect to database
        $dbh = db_connect();

        // Make conversation repository
        $conversation_repository = (new RepositoryFactory($dbh))->makeConversation();

        $this->response->body(number_format($conversation_repository->count()));
    }

    public function action_create()
	{
        // Connect to database
        $dbh = db_connect();

        // Instantiate start conversation interactor
        $interactor = (new InteractorFactory($dbh))->makeStartConversation();

        // Get user information
        $ip = $_SERVER['REMOTE_ADDR'];
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';

        // Start new conversation
        $conversation_id = $interactor->interact($ip, $user_agent);

        // Store conversation ID in session
        session_start();
        $_SESSION['conversation_id'] = $conversation_id;

        $this->response->body(json_encode(['conversation_id' => $conversation_id]));
    }
}
