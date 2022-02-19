<?php

namespace Melonly\Support\Debug;

class Timer
{
    protected string|float $start;

    protected string|float $stop;

    public function __construct()
    {
        $this->start = microtime(true);
    }

    public function stop(): float
    {
        $this->stop = microtime(true);

        return $this->stop - $this->start;
    }
}
