<?php

if (!function_exists('redirectBack')) {
    function redirectBack(array $data = []): never {
        foreach ($data as $key => $value) {
            $_SESSION['MELONLY_FLASH_' . $key] = $value;
        }

        if (!isset($_SERVER['HTTP_REFERER'])) {
            redirect('/');
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);

        exit;
    }
}
