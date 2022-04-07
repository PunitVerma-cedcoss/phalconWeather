<?php

namespace App\Components;

use Phalcon\Di\Injectable;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ClientException;

/**
 * helper class for hitting the api and getting response
 */
class SearchComponent extends Injectable
{
    /**
     * returns whatever the response of the passed url
     *
     * @param [string] $q
     * @return ARRAY
     */
    public function searchLocation($q)
    {
        if ($this->cache->has(implode("", explode(" ", $q)))) {
            return $this->cache->get(implode("", explode(" ", $q)));
        }
        $client = new Client();
        $qq = implode('+', explode(' ', $q));
        $key = $this->key;
        $url = "http://api.weatherapi.com/v1/search.json?key=" . $key . "&q=" . $q;
        $response = $client->get(
            $url,
        );
        $response = json_decode($response->getBody()->getContents(), true);
        $this->cache->set(implode("", explode(" ", $q)), $response);

        return $response;
    }
    /**
     * return locations data
     *
     * @param [string] $lending_edition_s
     * @param [string] $size
     * @return string
     */
    public function getDetailsByCity($city)
    {
        $client = new Client();
        $key = $this->key;
        $url = "http://api.weatherapi.com/v1/current.json?key=" . $key . "&q=" . $city . "&aqi=yes";
        $response;
        try {
            $response = $client->request(
                "GET",
                $url,
            );
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (ClientException $e) {
            // $s = explode("message", $e->getMessage())[1];
            // print_r(substr($s, 3, strlen($s) - 8));
            header("location:/index/error");
        }
    }
    /**
     * return forecast
     *
     * @param [string] $lending_edition_s
     * @param [string] $size
     * @return string
     */
    public function getForecastByCity($city)
    {
        $client = new Client();
        $key = $this->key;
        $url = "http://api.weatherapi.com/v1/forecast.json?key=" . $key . "&q=" . $city . "&days=1&aqi=no&alerts=yes";
        $response = '';
        try {
            $response = $client->request(
                "GET",
                $url,
            );
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (ClientException $e) {
            header("location:/index/error");
        }
    }
    /**
     * return history
     *
     * @param [string] $lending_edition_s
     * @param [string] $size
     * @return string
     */
    public function getHistory($city, $date)
    {
        // $tommarrow = date("Y-m-d", strtotime("-1 day"));
        $client = new Client();
        $key = $this->key;
        //http://api.weatherapi.com/v1/history.json?key=0bab7dd1bacc418689b143833220304&q=lucknow&dt=2022-04-06
        $url = "http://api.weatherapi.com/v1/history.json?key=" . $key . "&q=" . $city . "&dt=" . $date;
        $response = '';
        try {
            $response = $client->request(
                "GET",
                $url,
            );
            $response = json_decode($response->getBody()->getContents(), true);
            return $response;
        } catch (ClientException $e) {
            // header("location:/index/error");
        }
    }
}
