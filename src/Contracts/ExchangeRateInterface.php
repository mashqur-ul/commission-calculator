<?php


namespace Commission\Calculator\Contracts;


interface ExchangeRateInterface
{
    /**
     * @param $currency
     * @return mixed
     */
    public function getExchangeRate($currency) : float ;
}