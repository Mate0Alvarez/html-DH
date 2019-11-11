<?php

use Lentech\Botster\Factory\RepositoryFactory;

class Controller_AJAX_Log extends Controller
{
	public function action_index()
	{
        // Continue session
        session_start();

        // If conversation ID is not set
        if (! isset($_SESSION['conversation_id']))
        {
            return false;
        }

        // Get conversation ID
        $conversation_id = $_SESSION['conversation_id'];

        // Get database handler
        $dbh = db_connect();

        // Instantiate message repository
        $message_repository = (new RepositoryFactory($dbh))->makeMessage();

        // Get last message ID is not set
        if (! isset($_GET['last']))
        {
            // Get all conversation messages
            $messages = array_reverse($message_repository->getLatestInConversation($conversation_id));
        }
        else
        {
            // Get all in conversation after the message
            $messages = $message_repository->getInConversationAfterMessage($conversation_id, $_GET['last']);
        }

        // Create JSON data array
        $json['messages'] = [];

        foreach ($messages as $message)
        {
            $json['messages'][] = [
                'id' => (int) $message->id,
                'author_id' => (int) $message->author_id,
                'message' => $message->text,
            ];
        }

        $this->response->body(json_encode($json));
    }
}
