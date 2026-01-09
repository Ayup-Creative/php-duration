<?php

namespace AyupCreative\Duration\Features;

use Carbon\CarbonInterval;

trait Builders
{
    public static function zero(): self
    {
        return new self(0);
    }

    public static function minutes(int $minutes): self
    {
        return new self($minutes);
    }

    public static function hours(int $hours): self
    {
        return new self($hours * 60);
    }

    public static function hoursAndMinutes(int $hours, int $minutes): self
    {
        return new self(($hours * 60) + $minutes);
    }

    public static function fromCarbon(CarbonInterval $interval): self
    {
        return new self((int) $interval->totalMinutes);
    }
}
