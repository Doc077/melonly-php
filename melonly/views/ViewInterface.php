<?php

namespace Melonly\Views;

interface ViewInterface {
    public static function compile(string $file): string;

    public static function renderView(string $file, array $variables = []): void;

    public static function renderComponent(string $file): void;
}
