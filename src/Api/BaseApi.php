<?php


namespace Commission\Calculator\Api;


use GuzzleHttp\Client;

abstract class BaseApi
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = $client = new Client();
    }

}