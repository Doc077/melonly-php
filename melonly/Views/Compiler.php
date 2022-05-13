<?php

namespace Melonly\Views;

use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Math;
use Melonly\Support\Helpers\Regex;
use Melonly\Support\Helpers\Str;

class Compiler
{
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
        '{{' => '<?= esc(',
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

        '/\\[ ?csrf\\]/' => '<input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">',
        '/\\[ ?include ?(:?.*?) ?\\]/' => '<?php include "[rootPath]" . "/" . $1 ?>',

        '/\\[ ?# ?(:?.*?) #?\\]/' => '',
    ];

    public static function compile(string $file, array $variables = [], ?string $includePathRoot = null): string
    {
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
                    '/<' . $name . '( (.*?)="(.*?)") ?\/>/',
                    '<?php \Melonly\Views\View::renderComponent("' . $componentFile . '", [\'$2\' => \'$3\', \'$4\' => \'$5\', \'$6\' => \'$7\']); ?>',

                    $content,
                );

                /**
                 * Handle opening & closing tags.
                 */
                $content = Regex::replace(
                    '/<' . $name . '( (.*?)="(.*?)") ?>(?<slot>.*?)<\/' . $name . '>/',
                    '<?php \Melonly\Views\View::renderComponent("' . $componentFile . '", [\'$2\' => \'$3\', \'$4\' => \'$5\', \'$6\' => \'$7\']); ?>',

                    $content,
                );

                /**
                 * Get passed variables values.
                 */
                $content = Regex::replace('/=> \'\$(.*?)\'/', '=> $$1', $content);

                $content = Regex::replace('/"? (.*?)="\$(.*?)"?/', ', \'$1\' => $$2', $content);
                $content = Regex::replace('/" (.*?) =>/', ', \'$1 =>', $content);
                $content = Regex::replace('/, \' {7}/', '', $content);
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
}
