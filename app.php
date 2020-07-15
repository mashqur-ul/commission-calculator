#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Commission\Calculator\Api\BinApi;
use Commission\Calculator\Api\ExchangeRateApi;
use Commission\Calculator\Calculator;


$inputFile = __DIR__.'/'.$argv[1];
$binApi = new BinApi();
$exchangeRageApi = new ExchangeRateApi();

$calculator = new Calculator($inputFile, $binApi, $exchangeRageApi);
$commissionAmounts = $calculator->calculateCommission();

foreach ($commissionAmounts as $item) {
    echo $item;
    print "\n";
}
