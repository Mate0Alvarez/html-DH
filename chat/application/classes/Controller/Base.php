<?php

class Controller_Base extends Controller_Template
{
    public function before()
    {
        parent::before();

        $this->template->page = new View();
        $this->template->config = [
            'domainRoot' => URL::site(),
            'analyticsId' => Kohana::$config->load('google.analytics_id'),
        ];

        $this->page = $this->template->page;
    }
}