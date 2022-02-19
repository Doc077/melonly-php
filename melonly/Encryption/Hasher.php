<?php

namespace Melonly\Encryption;

use Exception;

class Hasher
{
    public function hash(string $data, int $cost = 10): string
    {
        $hash = password_hash($data, PASSWORD_BCRYPT, [
            'cost' => $cost
        ]);

        if (!$hash) {
            throw new Exception('Cannot create hash with Bcrypt');
        }

        return $hash;
    }

    public function check(string $input, string $output): bool
    {
        return password_verify($input, $output);
    }

    public function equals(string $input, string $output): bool
    {
        return hash_equals($input, $output);
    }
}
