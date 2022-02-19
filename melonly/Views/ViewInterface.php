<?php

namespace Melonly\Views;

interface ViewInterface
{
    public static function renderView(string $file, array $variables = [], bool $absolutePath = false, ?string $includePathRoot = null): void;

    public static function renderComponent(string $file): void;
}
