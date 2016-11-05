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
     * @param  $curl
     * @return void
     */
    public function preSend($curl);

    /**
     * execute multi
     */
    public function multi();

}
