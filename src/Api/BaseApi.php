<?php


namespace Commission\Calculator\Api;


use GuzzleHttp\Client;

abstract class BaseApi
{
    protected $client;

    public function __construct()
    {
        $this->client = $client = new Client();
    }

}