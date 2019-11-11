<?php

class Controller_AJAX_Online extends Controller
{
	public function action_index()
	{
        //Connect to database
        $dbh = db_connect();

        //get information
        session_start();
        $session_id = session_id();
        $ip = $_SERVER['REMOTE_ADDR'];
        if(isset($_SERVER['HTTP_USER_AGENT'])) { $user_agent = $_SERVER['HTTP_USER_AGENT']; } else { $user_agent = ''; }
        $time = time(); //Curent time
        $timeout = $time - 300; //5 minutes

        // Get session from database
        $query = $dbh->prepare('SELECT COUNT(*) AS count FROM online WHERE session_id = :session_id LIMIT 1');
        $query->execute([
            ':session_id' => $session_id,
        ]);
        $sessions = $query->fetchObject();

        // If session already exists
        if ($sessions->count == 1)
        {
            //update last active time
            $query = $dbh->prepare('UPDATE online SET time = :time WHERE session_id = :session_id');
            $query->execute([
                ':time' => $time,
                ':session_id' => $session_id,
            ]);
        }
        else
        {
            //add users session to databse
            $query = $dbh->prepare('INSERT INTO online VALUES (:session_id, :ip, :user_agent, :time)');
            $query->execute([
                ':session_id' => $session_id,
                ':ip' => $ip,
                ':user_agent' => $user_agent,
                ':time' => $time,
            ]);
        }

        //delete sessions that have been inactive for timeout period
        $query = $dbh->prepare('DELETE FROM online WHERE time < :timeout');
        $query->execute([
            ':timeout' => $timeout,
        ]);

        //Get number online
        $query = $dbh->query('SELECT COUNT(*) AS count FROM online');
        $online = $query->fetchObject();

        $this->response->body(number_format($online->count));
    }
}
