<?php


namespace Commission\Calculator\Api;


use Commission\Calculator\Contracts\BinInterface;

class BinApi extends BaseApi implements BinInterface
{
    private $BaseUrl = 'https://lookup.binlist.net/';
    private $bin;

    private function getBinInfo($bin)
    {
        $url = $this->BaseUrl . $bin;
        $response = $this->client->get($url);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);
        }

        throw new \Exception("BIN Information Could Not Be Fetched !");
    }

    public function getCountryShortName($bin)
    {
        $binInfo = $this->getBinInfo($bin);

        if (isset($binInfo["country"]["alpha2"])) {
            return $binInfo["country"]["alpha2"];
        }
    }
}