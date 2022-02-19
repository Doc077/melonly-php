<?php

namespace Melonly\Http;

use CurlHandle;
use Melonly\Support\Helpers\Json;

class Http
{
    protected static function initCurl(string $uri): CurlHandle|false
    {
        return curl_init($uri);
    }

    public static function get(string $uri, array|string $data = []): mixed
    {
        $curl = self::initCurl($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, Json::encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($curl);
        $data = Json::decode($response);

        curl_close($curl);

        return $data;
    }

    public static function post(string $uri, array|string $data = []): mixed
    {
        $curl = self::initCurl($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, Json::encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($curl);
        $data = Json::decode($response);

        curl_close($curl);

        return $data;
    }

    public static function put(string $uri, array|string $data = []): mixed
    {
        $curl = self::initCurl($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, Json::encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');

        $response = curl_exec($curl);
        $data = Json::decode($response);

        curl_close($curl);

        return $data;
    }

    public static function patch(string $uri, array|string $data = []): mixed
    {
        $curl = self::initCurl($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, Json::encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');

        $response = curl_exec($curl);
        $data = Json::decode($response);

        curl_close($curl);

        return $data;
    }

    public static function delete(string $uri, array|string $data = []): mixed
    {
        $curl = self::initCurl($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, Json::encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $response = curl_exec($curl);
        $data = Json::decode($response);

        curl_close($curl);

        return $data;
    }
}
