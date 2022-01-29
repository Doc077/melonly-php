<?php

namespace Melonly\Views;

use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Math;
use Melonly\Support\Helpers\Str;
use Melonly\Support\Helpers\Regex;

class View implements ViewInterface {
    protected static ?string $currentView = null;

    protected static array $namespaceAliases = [
        'Arr' => \Melonly\Support\Helpers\Arr::class,
        'Auth' => \Melonly\Authentication\Facades\Auth::class,
        'Crypt' => \Melonly\Encryption\Facades\Crypt::class,
        'DB' => \Melonly\Database\Facades\DB::class,
        'File' => \Melonly\Filesystem\File::class,
        'Hash' => \Melonly\Encryption\Facades\Hash::class,
        'HtmlNodeString' => \Melonly\Views\HtmlNodeString::class,
        'Http' => \Melonly\Http\Http::class,
        'Json' => \Melonly\Support\Helpers\Json::class,
        'Log' => \Melonly\Logging\Facades\Log::class,
        'Math' => \Melonly\Support\Helpers\Math::class,
        'Regex' => \Melonly\Support\Helpers\Regex::class,
        'Str' => \Melonly\Support\Helpers\Str::class,
        'Time' => \Melonly\Support\Helpers\Time::class,
        'Url' => \Melonly\Support\Helpers\Url::class,
        'Uuid' => \Melonly\Support\Helpers\Uuid::class,
        'Vector' => \Melonly\Support\Containers\Vector::class,
    ];

    protected static array $stringExpressions = [
        '{{!' => '<?=',
        '!}}' => '?>',
        '{{' => '<?= printData(',
        '}}' => ') ?>',
        '[[' => '<?= trans(',
        ']]' => ') ?>',
    ];

    protected static array $regexExpressions = [
        '/\\[ ?foreach ?(:?.*?) ?\\]/' => '<?php foreach ($1): ?>',
        '/\\[ ?endforeach ?\\]/' => '<?php endforeach; ?>',

        '/\\[ ?if ?(:?.*?(\\(.*?\\)*)?) ?\\]/' => '<?php if ($1): ?>',
        '/\\[ ?endif ?\\]/' => '<?php endif; ?>',

        '/\\[ ?else ?\\]/' => '<?php else: ?>',
        '/\\[ ?elseif ?(:?.*?(\\(.*?\\)*)?) ?\\]/' => '<?php elseif ($1): ?>',

        '/\\[ ?for ?(.*?) ?\\]/' => '<?php for ($1): ?>',
        '/\\[ ?endfor ?\\]/' => '<?php endfor; ?>',

        '/\\[ ?while ?(.*?) ?\\]/' => '<?php while ($1): ?>',
        '/\\[ ?endwhile ?\\]/' => '<?php endwhile; ?>',

        '/\\[ ?break ?\\]/' => '<?php break; ?>',
        '/\\[ ?continue ?\\]/' => '<?php continue; ?>',

        '/\\[csrf\\]/' => '<input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">',
        '/\\[include ?(:?.*?) ?\\]/' => '<?php include "[rootPath]" . "/" . $1 ?>',
    ];

    public static function compile(string $file, array $variables = [], ?string $includePathRoot = null): string {
        $content = File::content($file);

        /**
         * Replace template syntax with PHP code.
         */
        foreach (self::$stringExpressions as $key => $value) {
            $content = Str::replace($key, $value, $content);
        }

        foreach (self::$regexExpressions as $key => $value) {
            if ($includePathRoot !== null) {
                /**
                 * Escape slashes provided by __DIR__.
                 */
                $root = str_replace('\\', '\\\\\\', $includePathRoot);

                $content = Regex::replace($key, str_replace('[rootPath]', $root, $value), $content);

                continue;
            }

            $content = Regex::replace($key, $value, $content);
        }

        /**
         * Add necessary namespaces.
         */
        foreach (self::$namespaceAliases as $key => $value) {
            $content = Str::replace($key . '::', $value . '::', $content);
        }

        /**
         * Extract variables passed to a view and pass them further to component.
         */
        $variablesAsString = '';

        foreach ($variables as $variable => $value) {
            if (is_array($value)) {
                $variablesAsString .= '"' . $variable . '" => [' . implode(',', $value) . '],';

                continue;
            }

            $variablesAsString .= '"' . $variable . '" => "' . $value . '",';
        }

        /**
         * Get all registered components and compile component tags.
         */
        if (File::exists(__DIR__ . '/../../frontend/views/components')) {
            $componentFiles = array_diff(scandir(__DIR__ . '/../../frontend/views/components'), ['.', '..']);

            foreach ($componentFiles as $componentFile) {
                $name = explode('.html', $componentFile)[0];

                /**
                 * Handle self-closing tags.
                 */
                $content = Regex::replace(
                    '/<' . $name . '( (.*)="(.*)")* ?\/>/',
                    '<?php \Melonly\Views\View::renderComponent("' . $componentFile . '", [' . $variablesAsString . '\'$2\' => \'$3\', \'$4\' => \'$5\']); ?>',

                    $content,
                );

                /**
                 * Handle opening & closing tags.
                 */
                $content = Regex::replace(
                    '/<' . $name . '( (.*)="(.*)")* ?>(?<slot>.*?)<\/' . $name . '>/',
                    '<?php \Melonly\Views\View::renderComponent("' . $componentFile . '", [' . $variablesAsString . '\'$2\' => \'$3\', \'$4\' => \'$5\']); ?>',

                    $content,
                );
            }
        }

        /**
         * Generate random file name and save compiled view.
         */
        $filename = random_bytes(16);
        $filename = __DIR__ . '/../../storage/temp/' . Math::binToHex($filename) . '.html';

        File::put($filename, $content);

        return $filename;
    }

    public static function renderView(string $file, array $variables = [], bool $absolutePath = false, ?string $includePathRoot = null): void {
        if (!File::exists(__DIR__ . '/../../frontend/views/' . $file . '.html')) {
            //throw new ViewNotFoundException("View '$file' does not exist");
        }

        if (!$absolutePath) {
            $file = __DIR__ . '/../../frontend/views/' . $file . '.html';
        }

        self::$currentView = $file;

        $compiled = self::compile($file, $variables, $includePathRoot);

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

        $compiled = self::compile($file);

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
