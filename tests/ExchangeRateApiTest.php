<?php

namespace Commission\Calculator\Tests;

use Commission\Calculator\Api\ExchangeRateApi;
use GuzzleHttp\Exception\ConnectException;
use PHPUnit\Framework\TestCase;

final class ExchangeRateApiTest extends TestCase
{
    private $exchangeRateApi;
    private $currency = "EUR";

    protected function setUp(): void
    {
        $this->exchangeRateApi = new ExchangeRateApi();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->exchangeRateApi);
        parent::tearDown();
    }

    public function testExchangeRateApiSuccessRequest()
    {
        try {
            $response = $this->exchangeRateApi->getExchangeRate($this->currency);
            $this->assertTrue(is_float($response));
        } catch (ConnectException $e) {
            $this->markTestIncomplete(
                'Test remains incomplete due to internet connection error'
            );
        }

    }
}