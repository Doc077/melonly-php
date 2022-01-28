<?php

namespace Melonly\Http;

class Http {
    public function get(string $uri, array | string $data = []): mixed {
        $curl = curl_init($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($curl);
        $data = json_decode($response);

        curl_close($curl);

        return $data;
    }

    public function post(string $uri, array | string $data = []): mixed {
        $curl = curl_init($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

        $response = curl_exec($curl);
        $data = json_decode($response);

        curl_close($curl);

        return $data;
    }

    public function put(string $uri, array | string $data = []): mixed {
        $curl = curl_init($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');

        $response = curl_exec($curl);
        $data = json_decode($response);

        curl_close($curl);

        return $data;
    }

    public function patch(string $uri, array | string $data = []): mixed {
        $curl = curl_init($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');

        $response = curl_exec($curl);
        $data = json_decode($response);

        curl_close($curl);

        return $data;
    }

    public function delete(string $uri, array | string $data = []): mixed {
        $curl = curl_init($uri);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $response = curl_exec($curl);
        $data = json_decode($response);

        curl_close($curl);

        return $data;
    }
}
