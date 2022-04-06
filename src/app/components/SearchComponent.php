<?php

namespace App\Components;

use Phalcon\Di\Injectable;
use GuzzleHttp\Client;

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
    public function search($q)
    {
        if ($this->cache->has(implode("", explode(" ", $q)))) {
            return $this->cache->get(implode("", explode(" ", $q)));
        }
        $client = new Client();
        $qq = implode('+', explode(' ', $q));
        $url = "https://openlibrary.org/search.json?q={$qq}&mode=ebooks&has_fulltext=true";
        $response = $client->get(
            $url,
        );
        $response = json_decode($response->getBody()->getContents(), true);
        $this->cache->set(implode("", explode(" ", $q)), $response);

        return $response;
    }
    /**
     * return an image path
     *
     * @param [string] $lending_edition_s
     * @param [string] $size
     * @return string
     */
    public function getImage($lending_edition_s, $size)
    {
        $image = "https://covers.openlibrary.org/b/olid/{$lending_edition_s}-{$size}.jpg";
        return $image;
    }
    public function getBookById($q)
    {
        $client = new Client();
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:{$q}&jscmd=details&format=json";
        $response = $client->get(
            $url,
        );
        $response = json_decode($response->getBody()->getContents(), true);
        return $response;
    }
}
