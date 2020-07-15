<?php


namespace Commission\Calculator\Api;


class BinApi extends BaseApi
{
    private $BaseUrl = 'https://lookup.binlist.net/';

    public function getBinInfo($bin)
    {
        $url = $this->BaseUrl . $bin;

        return $this->client->get($url);
    }
}