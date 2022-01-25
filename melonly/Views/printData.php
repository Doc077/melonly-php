<?php

use Melonly\Views\HtmlNodeString;

if (!function_exists('printData')) {
    function printData(mixed $data): void {
        if ($data instanceof HtmlNodeString) {
            print($data);

            return;
        }

        print(htmlspecialchars($data));
    }
}
