<?php


namespace Commission\Calculator\Contracts;


interface BinInterface
{
    /**
     * @param $bin
     * @return mixed
     */
    public function getCountryShortName($bin);
}