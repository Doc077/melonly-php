<?php

if (!function_exists('redirectBack')) {
    function redirectBack(array $data = []): never {
        foreach ($data as $key => $value) {
            Session::set('MELONLY_FLASH_' . $key, $value);
        }

        if (!isset($_SERVER['HTTP_REFERER'])) {
            throw new Exception('Cannot redirect to previous location');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit();
    }
}