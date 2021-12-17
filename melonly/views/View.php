<?php

namespace Melonly\Views;

class View implements ViewInterface {
    public static function compile(string $file): string {
        $content = file_get_contents($file);

        $stringExpressions = [
            '{{{' => '<?=',
            '}}}' => '?>',
            '{{' => '<?= htmlspecialchars(',
            '}}' => ') ?>',
            '[[' => '<?= trans(',
            ']]' => ') ?>'
        ];

        foreach ($stringExpressions as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        $regexExpressions = [
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
            '/\\[ ?continue ?\\]/' => '<?php continue; ?>'
        ];

        foreach ($regexExpressions as $key => $value) {
            $content = preg_replace($key, $value, $content);
        }

        /**
         * Add necessary namespaces.
         */
        $namespaces = [
            'Arr' => 'Melonly\Support\Helpers\Arr',
            'Auth' => 'Melonly\Authentication\Auth',
            'DB' => 'Melonly\Database\DB',
            'File' => 'Melonly\Filesystem\File',
            'Http' => 'Melonly\Http\Http',
            'Str' => 'Melonly\Support\Helpers\Str',
            'Time' => 'Melonly\Support\Helpers\Time',
            'Url' => 'Melonly\Http\Url',
            'Vector' => 'Melonly\Support\Containers\Vector',
        ];

        foreach ($namespaces as $key => $value) {
            $content = str_replace($key . '::', $value . '::', $content);
        }

        /**
         * Generate random file name and save compiled view.
         */
        $filename = random_bytes(16);
        $filename = __DIR__ . '/../storage/views/' . bin2hex($filename) . '.html';

        file_put_contents($filename, $content);

        return $filename;
    }
}
