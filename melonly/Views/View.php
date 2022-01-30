<?php

namespace Melonly\Views;

use Melonly\Filesystem\File;

class View implements ViewInterface {
    protected static ?string $currentView = null;

    public static function renderView(string $file, array $variables = [], bool $absolutePath = false, ?string $includePathRoot = null): void {
        if (!File::exists(__DIR__ . '/../../frontend/views/' . $file . '.html') && !File::exists($file)) {
            throw new ViewNotFoundException("View '$file' does not exist");
        }

        if (!$absolutePath) {
            $file = __DIR__ . '/../../frontend/views/' . $file . '.html';
        }

        self::$currentView = $file;

        $compiled = Compiler::compile($file, $variables, $includePathRoot);

        /**
         * Get passed variables and include compiled view.
         */
        extract($variables);

        ob_start();

        include $compiled;

        /**
         * Remove temporary file.
         */
        File::delete($compiled);
    }

    public static function renderComponent(string $file, array $attributes = []): void {
        if (!File::exists(__DIR__ . '/../../frontend/views/components/' . $file)) {
            throw new ComponentNotFoundException("Component '$file' does not exist");
        }

        $file = __DIR__ . '/../../frontend/views/components/' . $file;

        self::$currentView = $file;

        $compiled = Compiler::compile($file);

        /**
         * Get passed variables and include compiled view.
         */
        extract($attributes);

        ob_start();

        include $compiled;

        /**
         * Remove temporary file.
         */
        File::delete($compiled);
    }

    public static function getCurrentView(): string {
        return self::$currentView;
    }

    public static function clearBuffer(): void {
        if (ob_get_contents()) {
            ob_end_clean();
        }
    }
}
