<?php

class Controller_Errors extends Controller_Base
{
    public function action_404()
    {
        $this->response->status(404);

        $this->template->title = 'Not found';
        $this->page->set_filename('errors/404');
    }
}
