<?php

namespace Melonly\Views;

use Exception;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Str;
use Melonly\Support\Helpers\Regex;

class View implements ViewInterface {
    public static ?string $currentView = null;

    protected static array $namespaces = [
        'Arr' => \Melonly\Support\Helpers\Arr::class,
        'Auth' => \Melonly\Authentication\Auth::class,
        'DB' => \Melonly\Database\DB::class,
        'File' => \Melonly\Filesystem\File::class,
        'HtmlNodeString' => \Melonly\Views\HtmlNodeString::class,
        'Str' => \Melonly\Support\Helpers\Str::class,
        'Time' => \Melonly\Support\Helpers\Time::class,
        'Url' => \Melonly\Support\Helpers\Url::class,
        'Vector' => \Melonly\Support\Containers\Vector::class
    ];

    protected static array $stringExpressions = [
        '{{{' => '<?=',
        '}}}' => '?>',
        '{{' => '<?= printData(',
        '}}' => ') ?>',
        '[[' => '<?= trans(',
        ']]' => ') ?>'
    ];

    protected static array $regexExpressions = [
        '/\\[ ?foreach ?(:?.*?) ?\\]/' => '<?php foreach ($1): ?>',
        '/\\[ ?endforeach ?\\]/' => '<?php endforeach; ?>',

        '/\\[ ?if ?(:?.*?(\\(.*?\\)*)?) ?\\]/' => '<?php if ($1): ?>',
        '/\\[ ?endif ?\\]/' => '<?php endif; ?>',

        '/\\[ ?elseif ?(:?.*?(\\(.*?\\)*)?) ?\\]/' => '<?php elseif ($1): ?>',
        '/\\[ ?else ?\\]/' => '<?php else: ?>',

        '/\\[ ?for ?(.*?) ?\\]/' => '<?php for ($1): ?>',
        '/\\[ ?endfor ?\\]/' => '<?php endfor; ?>',

        '/\\[ ?while ?(.*?) ?\\]/' => '<?php while ($1): ?>',
        '/\\[ ?endwhile ?\\]/' => '<?php endwhile; ?>',

        '/\\[ ?break ?\\]/' => '<?php break; ?>',
        '/\\[ ?continue ?\\]/' => '<?php continue; ?>',

        '/\\[csrf\\]/' => '<input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">'
    ];

    public static function compile(string $file, array $variables = []): string {
        $content = File::content($file);

        /**
         * Replace template syntax with PHP code.
         */
        foreach (self::$stringExpressions as $key => $value) {
            $content = Str::replace($key, $value, $content);
        }

        foreach (self::$regexExpressions as $key => $value) {
            $content = Regex::replace($key, $value, $content);
        }

        /**
         * Add necessary namespaces.
         */
        foreach (self::$namespaces as $key => $value) {
            $content = Str::replace($key . '::', $value . '::', $content);
        }

        /**
         * Extract variables passed to a view and pass them to component.
         */
        $variablesAsString = '';

        foreach ($variables as $variable => $value) {
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

                    $content
                );

                /**
                 * Handle opening & closing tags.
                 */
                $content = Regex::replace(
                    '/<' . $name . '( (.*)="(.*)")* ?>(?<slot>.*?)<\/' . $name . '>/',
                    '<?php \Melonly\Views\View::renderComponent("' . $componentFile . '", [' . $variablesAsString . '\'$2\' => \'$3\', \'$4\' => \'$5\']); ?>',

                    $content
                );
            }
        }

        /**
         * Generate random file name and save compiled view.
         */
        $filename = random_bytes(16);
        $filename = __DIR__ . '/../storage/views/' . bin2hex($filename) . '.html';

        File::put($filename, $content);

        return $filename;
    }

    public static function renderView(string $file, array $variables = []): void {
        if (!File::exists(__DIR__ . '/../../frontend/views/' . $file . '.html')) {
            throw new Exception("View '$file' does not exist");
        }

        $file = __DIR__ . '/../../frontend/views/' . $file . '.html';

        self::$currentView = $file;

        $compiled = self::compile($file, $variables);

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
            throw new Exception("Component '$file' does not exist");
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

    public static function clearBuffer(): void {
        if (ob_get_contents()) {
            ob_end_clean();
        }
    }
}
