<?php

function dump(...$variables): void {
    echo '<div style="padding: 14px 16px; background: #2a2b32; color: #ddd; font-family: Cascadia Mono, Consolas, monospace; font-size: 14px; border-radius: 10px; line-height: 1.6;">';
    echo '<div style="color: #b8bcc9; margin-bottom: 8px;">Dumped variables (' . count($variables) . '):</div>';

    foreach ($variables as $variable) {
        $type = gettype($variable);

        $value = $variable;

        switch ($type) {
            case 'array':
                $value = '[' . implode(', ', $value) . ']' . '<span style="color: #e491fd;"> / ' . count($value) . ' item(s)</span>';

                break;

            case 'string':
                $value = "'{$value}'" . '<span style="color: #e491fd;"> / ' . strlen($value) . ' character(s)</span>';

                break;

            case 'boolean':
                $value = $value ? 'true' : 'false';

                break;

            case 'object':
                $type = get_class($variable);

                $value = 'new ' . get_class($variable) . '()';

                break;

            case 'NULL':
                $value = 'null';
                $type = 'null';

                break;
        }

        $arrayTypes = [];

        if (is_array($variable)) {
            $types = [];

            foreach ($variable as $element) {
                if (gettype($element) === 'object') {
                    $types[] = get_class($element);

                    continue;
                }

                $types[] = strtolower(gettype($element));
            }

            $types = array_unique($types);

            $arrayTypes = implode(', ', $types);
        }

        echo '<div><span style="color: #e491fd;">' . $type . '</span>' . (is_array($variable) ? "&lt;<span style=\"color: #8ec6ff;\">{$arrayTypes}</span>&gt;" : '') . ': <span style="color: #a1cf7f;">' . (is_array($variable) ? '[' : $value) . '</span></div>';

        if (is_array($variable)) {
            foreach ($variable as $element) {
                echo '<div style="width: 10px; margin-left: 20px;">';

                switch (gettype($element)) {
                    case 'array':
                        echo 'array';
        
                        break;
        
                    case 'string':
                        echo $element;
        
                        break;
        
                    case 'boolean':
                        echo $value ? 'true' : 'false';
        
                        break;
        
                    case 'object':
                        $type = get_class($element);
        
                        echo 'new ' . get_class($element) . '()';
        
                        break;
        
                    case 'NULL':
                        echo 'null';
        
                        break;
                }

                echo '</div>';
            }
        }
    }

    echo '</div>';
}
