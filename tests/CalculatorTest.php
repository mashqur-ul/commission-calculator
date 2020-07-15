<?php

namespace Commission\Calculator\Tests;

use Commission\Calculator\Api\BinApi;
use Commission\Calculator\Api\ExchangeRateApi;
use Commission\Calculator\Calculator;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    private $inputFile = __DIR__.'/input.txt';

    public function testExchangeRateApiRequestFailedExceptionIsRaised()
    {
        $binApi = new BinApi();
        $exchangeRateApi = new MockExchangeRateApi(404, "");
        $calculator = new Calculator($this->inputFile, $binApi, $exchangeRateApi);
        $this->expectException(ClientException::class);
        $calculator->calculateCommission();
    }

    public function testBinApiRequestFailedExceptionIsRaised()
    {
        $binApi = new MockBinApi(404, "");
        $exchangeRateApi = new ExchangeRateApi();
        $calculator = new Calculator($this->inputFile, $binApi, $exchangeRateApi);
        $this->expectException(ClientException::class);
        $calculator->calculateCommission();
    }

    public function testCommissionAmount()
    {
        $binApiMockData = file_get_contents(__DIR__.'/Mock/bin_api_body.txt');
        $binApi = new MockBinApi(200, $binApiMockData);

        $exchangeRateApiMockData = file_get_contents(__DIR__.'/Mock/exchange_rate_api_body.txt');
        $exchangeRateApi = new MockExchangeRateApi(200, $exchangeRateApiMockData);


        $expected = [1];
        $calculator = new Calculator($this->inputFile, $binApi, $exchangeRateApi);
        $commissionAmount = $calculator->calculateCommission();

        $this->assertTrue(empty(array_diff($expected, $commissionAmount)));
    }
}


class MockBinApi extends BinApi {

    public function __construct($status, $body)
    {
        parent::__construct();

        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
    }
}

class MockExchangeRateApi extends ExchangeRateApi {

    public function __construct($status, $body)
    {
        parent::__construct();

        $mock = new MockHandler([new Response($status, [], $body)]);
        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);
    }
}