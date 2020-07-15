<?php


namespace Commission\Calculator;


use Commission\Calculator\Api\BinApi;
use Commission\Calculator\Api\ExchangeRateApi;

class Calculator
{
    private $inputFile;
    private $binApi;
    private $exchangeRateApi;
    private $euCommissionRate = 0.01;
    private $nonEuCommissionRate = 0.02;
    private $europeanCountries = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct($inputFile, BinApi $binApi, ExchangeRateApi $exchangeRateApi)
    {
        $this->inputFile = $inputFile;
        $this->binApi = $binApi;
        $this->exchangeRateApi = $exchangeRateApi;
    }

    private function getFileData()
    {
        $fileReader = new FileReader();
        return $fileReader->setFilePath($this->inputFile)->getData();
    }

    private function isEu($bin)
    {
        $response = $this->binApi->getBinInfo($bin);
        if ($response->getStatusCode() == 200) {
            $binInfo = json_decode($response->getBody(), true);
            return in_array($binInfo["country"]["alpha2"], $this->europeanCountries);
        }
    }

    private function getExchangeRate($currency)
    {
        $response = $this->exchangeRateApi->getExchangeRates();

        if ($response->getStatusCode() == 200) {
            $exchangeRates = json_decode($response->getBody(), true);
            return $exchangeRates["rates"][$currency] ?? 0;
        }
    }

    private function commissionAmount($transaction, $isEu, $rate)
    {
        if ($transaction->currency == 'EUR' || $rate == 0) {
            $amountFixed = $transaction->amount;
        }

        if ($transaction->currency != 'EUR' || $rate > 0) {
            $amountFixed = $transaction->amount / $rate;
        }

        return round(($amountFixed * ($isEu ? $this->euCommissionRate : $this->nonEuCommissionRate)), 2);
    }

    public function calculateCommission()
    {
        $commissionAmount = [];
        $transactions = $this->getFileData();

        foreach ($transactions as $item) {

            if (empty($item)) {
                continue;
            }

            $transaction = json_decode($item);
            $isEu = $this->isEu($transaction->bin);
            $rate = $this->getExchangeRate($transaction->currency);
            $commissionAmount[] = $this->commissionAmount($transaction, $isEu, $rate);
        }

        return $commissionAmount;
    }
}