<?php

namespace Melonly\Http;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Http {
    protected ?Client $client = null;

    public function __construct() {
        $this->client = new Client();
    }

    public function get($uri): ResponseInterface {
        $response = $this->client->get($uri);

        return $response;
    }

    public function post($uri): ResponseInterface {
        $response = $this->client->post($uri);

        return $response;
    }

    public function put($uri): ResponseInterface {
        $response = $this->client->put($uri);

        return $response;
    }

    public function patch($uri): ResponseInterface {
        $response = $this->client->patch($uri);

        return $response;
    }

    public function delete($uri): ResponseInterface {
        $response = $this->client->delete($uri);

        return $response;
    }

    public function head($uri): ResponseInterface {
        $response = $this->client->head($uri);

        return $response;
    }

    public function options($uri): ResponseInterface {
        $response = $this->client->options($uri);

        return $response;
    }
}
