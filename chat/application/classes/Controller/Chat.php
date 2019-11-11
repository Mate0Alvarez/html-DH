<?php

class Controller_Chat extends Controller_Base
{
	public function action_index()
	{
        $this->template->title = 'Botster - The friendly, fun, quirky chatbot';
        $this->page->set_filename('chat');
	}
}
