<?php


namespace Commission\Calculator;

use Commission\Calculator\Contracts\BinInterface;
use Commission\Calculator\Contracts\ExchangeRateInterface;

class Calculator
{
    /**
     * @var
     */
    private $inputFile;
    /**
     * @var BinInterface
     */
    private $binApi;
    /**
     * @var ExchangeRateInterface
     */
    private $exchangeRateApi;
    /**
     * @var float
     */
    private $euCommissionRate = 0.01;
    /**
     * @var float
     */
    private $nonEuCommissionRate = 0.02;
    /**
     * @var string[]
     */
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

    /**
     * Calculator constructor.
     * @param $inputFile
     * @param BinInterface $binApi
     * @param ExchangeRateInterface $exchangeRateApi
     */
    public function __construct($inputFile, BinInterface $binApi, ExchangeRateInterface $exchangeRateApi)
    {
        $this->inputFile = $inputFile;
        $this->binApi = $binApi;
        $this->exchangeRateApi = $exchangeRateApi;
    }

    /**
     * @return array|false|string[]
     */
    private function getFileData()
    {
        $fileReader = new FileReader();
        return $fileReader->setFilePath($this->inputFile)->getData();
    }

    /**
     * @param $bin
     * @return bool
     */
    private function isEu($bin)
    {
        $countryShortName = $this->binApi->getCountryShortName($bin);
        return in_array($countryShortName, $this->europeanCountries);
    }

    /**
     * @param $transaction
     * @param $isEu
     * @param $rate
     * @return float
     */
    private function commissionAmount($transaction, $isEu, $rate)
    {
        if ($transaction->currency == 'EUR' || $rate == 0) {
            $amountFixed = $transaction->amount;
        }

        if ($transaction->currency != 'EUR' || $rate > 0) {
            $amountFixed = bcdiv($transaction->amount, $rate, 6);
        }

        $commissionRate = $isEu ? $this->euCommissionRate : $this->nonEuCommissionRate;
        return round(bcmul($amountFixed, $commissionRate, 6), 2);
    }

    /**
     * @return array
     */
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
            $rate = $this->exchangeRateApi->getExchangeRate($transaction->currency);
            $commissionAmount[] = $this->commissionAmount($transaction, $isEu, $rate);
        }

        return $commissionAmount;
    }
}