<?php

class HTTP_Exception_404 extends Kohana_HTTP_Exception_404
{
    public function get_response()
    {
        return (new Request('errors/404'))->execute();
    }
}
