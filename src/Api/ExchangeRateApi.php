<?php


namespace Commission\Calculator\Api;


class ExchangeRateApi extends BaseApi
{
    private $apiUrl = 'https://api.exchangeratesapi.io/latest';

    public function getExchangeRates()
    {
        return $this->client->get($this->apiUrl);
    }
}