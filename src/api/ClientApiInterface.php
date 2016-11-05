<?php

namespace SimpleApi;

interface ClientApiInterface
{
    /**
     * send
     * @return json
     */
    public function send();

    /**
     * @param  $curl
     * @return void
     */
    public function preSend($curl);

    /**
     * multi
     *
     */
    public function multi();
}
