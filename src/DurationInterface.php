<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

interface DurationInterface
{
    /**
     * Get the total duration in seconds.
     *
     * @return int
     */
    public function totalSeconds(): int;
}
