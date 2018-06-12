<?php

namespace AppBundle\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ApiRequestService
{
    private $apiRequestClient;

    /**
     * ApiRequestService constructor.
     * @param $offers_api_url
     */
    public function __construct($offers_api_url)
    {
        try {
            $this->apiRequestClient = new Client([
                // Base URI is used with relative requests
                'base_uri' => $offers_api_url,
                // You can set any number of default request options.
                'timeout' => 2.0,
            ]);
        } catch (RequestException $e) {
            throw $e;
        }
    }

    /**
     * @return Client
     */
    public function getApiRequestClient()
    {
        return $this->apiRequestClient;
    }
}