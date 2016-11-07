<?php

namespace SimpleApi;

interface ClientApiInterface
{
    /**
     * send
     * @return array
     */
    public function send();

    /**
     * @return void
     */
    public function preSend();

    /**
     * post send (reset)
     */
    public function postSend();

    /**
     * execute multi
     */
    public function multi();

    /**
     * post multi
     */
    public function postMulti();

}
