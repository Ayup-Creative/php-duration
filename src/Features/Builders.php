<?php

namespace AyupCreative\Duration\Features;

use Carbon\CarbonInterval;

trait Builders
{
    public static function zero(): self
    {
        return new self(0);
    }

    public static function seconds(int $seconds): self
    {
        return new self($seconds);
    }

    public static function minutes(int $minutes): self
    {
        return new self($minutes * self::SECONDS_PER_MINUTE);
    }

    public static function hours(int $hours): self
    {
        return new self($hours * self::SECONDS_PER_HOUR);
    }

    public static function days(int $days): self
    {
        return new self($days * self::SECONDS_PER_DAY);
    }

    public static function weeks(int $weeks): self
    {
        return new self($weeks * self::SECONDS_PER_WEEK);
    }

    public static function months(int $months): self
    {
        return new self($months * self::SECONDS_PER_MONTH);
    }

    public static function years(int $years): self
    {
        return new self($years * self::SECONDS_PER_YEAR);
    }

    public static function hoursAndMinutes(int $hours, int $minutes): self
    {
        return new self(($hours * self::SECONDS_PER_HOUR) + ($minutes * self::SECONDS_PER_MINUTE));
    }

    public static function make(int $days = 0, int $hours = 0, int $minutes = 0, int $seconds = 0): self
    {
        $seconds += ($hours * self::SECONDS_PER_HOUR) +
            ($minutes * self::SECONDS_PER_MINUTE) +
            ($days * self::SECONDS_PER_DAY);

        return new self($seconds);
    }

    public static function fromCarbon(CarbonInterval $interval): self
    {
        return new self((int) $interval->totalSeconds);
    }
}
