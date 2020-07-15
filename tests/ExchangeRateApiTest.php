<?php

namespace Commission\Calculator\Tests;

use Commission\Calculator\Api\ExchangeRateApi;
use PHPUnit\Framework\TestCase;

final class ExchangeRateApiTest extends TestCase
{
    private $exchangeRateApi;

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
        $response = $this->exchangeRateApi->getExchangeRates();
        $this->assertTrue($response->getStatusCode() === 200);
    }
}