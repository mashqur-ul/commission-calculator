<?php

namespace Commission\Calculator\Tests;

use Commission\Calculator\Api\BinApi;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\TestCase;

final class BinApiTest extends TestCase
{
    private $binApi;
    private $bin = 45717360;


    protected function setUp(): void
    {
        $this->binApi = new BinApi();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($this->binApi);
        parent::tearDown();
    }

    public function testBinApiSuccessRequest()
    {
        $response = $this->binApi->getCountryShortName($this->bin);
        $this->assertTrue(!empty($response) && is_string($response));
    }
}