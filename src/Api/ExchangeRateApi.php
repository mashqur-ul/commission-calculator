<?php


namespace Commission\Calculator\Api;


use Commission\Calculator\Contracts\ExchangeRateInterface;

class ExchangeRateApi extends BaseApi implements ExchangeRateInterface
{
    /**
     * @var string
     */
    private $apiUrl = 'https://api.exchangeratesapi.io/latest';

    /**
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getLatestRates()
    {
        $response = $this->client->get($this->apiUrl);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        throw new \Exception("Latest Exchange Rates Could Not Be Fetched !");
    }

    /**
     * @param $currency
     * @return int|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getExchangeRate($currency) : float
    {
        $latestExchangeRates = $this->getLatestRates();
        if (isset($latestExchangeRates['rates']) && !empty($latestExchangeRates['rates'])) {
            return $latestExchangeRates["rates"][$currency] ?? 0;
        }
    }
}