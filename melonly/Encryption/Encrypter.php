<?php

namespace Melonly\Encryption;

class Encrypter
{
    protected string $key;

    public function __construct()
    {
        $this->key = config('encryption.key');
    }

    public function encrypt(string $data, string $algorithm = 'aes-256-ctr', bool $encode = false): string
    {
        $size = openssl_cipher_iv_length($algorithm);
        $nonce = openssl_random_pseudo_bytes($size);

        $cipherText = openssl_encrypt(
            $data,
            $algorithm,
            $this->key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        if ($encode) {
            return base64_encode($nonce . $cipherText);
        }

        return $nonce . $cipherText;
    }

    public function decrypt(string $data, string $algorithm = 'aes-256-ctr', bool $encoded = false): string
    {
        if ($encoded) {
            $data = base64_decode($data, true);

            if ($data === false) {
                throw new EncryptionException('Cannot decrypt data');
            }
        }

        $size = openssl_cipher_iv_length($algorithm);

        $nonce = mb_substr($data, 0, $size, '8bit');
        $cipherData = mb_substr($data, $size, null, '8bit');

        $plainData = openssl_decrypt(
            $cipherData,
            $algorithm,
            $this->key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plainData;
    }
}
