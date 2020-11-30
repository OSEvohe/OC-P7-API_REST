<?php


namespace App\Service;


use App\Exception\ApiMalformedJsonException;

class DataHelper
{
    public function jsonDecode($json){
        if (null === $data = json_decode($json, true)) {
            throw new ApiMalformedJsonException();
        }
        return $data;
    }
}