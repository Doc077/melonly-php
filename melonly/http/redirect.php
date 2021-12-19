<?php

if (!function_exists('redirect')) {
    function redirect(string $url, array $data = []): void {
        foreach ($data as $key => $value) {
            $_SESSION['MELONLY_FLASH_' . $key] = $value;
        }

        header('Location: ' . $url);

        exit;
    }
}
