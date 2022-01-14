<?php

namespace Melonly\Encryption;

use Exception;

class Encrypter {
    protected string $key;

    public function __construct() {
        $this->key = env('ENCRYPT_KEY');
    }

    public function encrypt(string $data, string $method = 'aes-256-ctr', bool $encode = false) {
        $size = openssl_cipher_iv_length($method);
        $nonce = openssl_random_pseudo_bytes($size);

        $cipherText = openssl_encrypt(
            $data,
            $method,
            $this->key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        if ($encode) {
            return base64_encode($nonce . $cipherText);
        }

        return $nonce . $cipherText;
    }

    public function decrypt(string $data, string $method = 'aes-256-ctr', bool $encoded = false) {
        if ($encoded) {
            $data = base64_decode($data, true);

            if ($data === false) {
                throw new EncryptionException('Cannot decrypt data');
            }
        }

        $nonceSize = openssl_cipher_iv_length($method);
        $nonce = mb_substr($data, 0, $nonceSize, '8bit');
        $cipherData = mb_substr($data, $nonceSize, null, '8bit');

        $plainData = openssl_decrypt(
            $cipherData,
            $method,
            $this->key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plainData;
    }
}
