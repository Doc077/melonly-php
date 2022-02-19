<?php

namespace Melonly\Translation;

use Exception;
use Melonly\Filesystem\File;
use Melonly\Support\Helpers\Json;

class Translator
{
    protected $currentLanguage = 'en';

    public function getCurrent(): string
    {
        return $this->currentLanguage;
    }

    public function setCurrent(string $lang): void
    {
        $this->currentLanguage = $lang;
    }

    public function getTranslation(string $key): string
    {
        $parts = explode('.', $key);

        $file = __DIR__ . '/../../frontend/lang/' . $this->getCurrent() . '/' . $parts[0] . '.json';

        if (!File::exists($file)) {
            throw new Exception("Translation file '{$parts[0]}' does not exist");
        }

        $json = Json::decode(File::content($file), true);

        if (!array_key_exists($parts[1], $json)) {
            return $key;
        }

        return $json[$parts[1]];
    }
}
