<?php

namespace Melonly\Views;

use Stringable;

class HtmlNodeString implements Stringable
{
    public function __construct(protected string $content)
    {
    }

    public function __toString(): string
    {
        return $this->content;
    }
}
