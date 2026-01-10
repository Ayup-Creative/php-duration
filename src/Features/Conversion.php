<?php

namespace AyupCreative\Duration\Features;

use DateInterval;

trait Conversion
{
    public function toSeconds(): int
    {
        return $this->totalSeconds;
    }

    public function toMinutes(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_MINUTE;
    }

    public function toHours(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_HOUR;
    }

    public function toDays(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_DAY;
    }

    public function toWeeks(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_WEEK;
    }

    public function toMonths(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_MONTH;
    }

    public function toYears(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_YEAR;
    }

    public function toCarbonInterval(): \Carbon\CarbonInterval
    {
        return \Carbon\CarbonInterval::seconds($this->totalSeconds);
    }

    public function toDateInterval(): DateInterval
    {
        $seconds = abs($this->totalSeconds);

        $days    = intdiv($seconds, self::SECONDS_PER_DAY);
        $seconds %= self::SECONDS_PER_DAY;

        $hours   = intdiv($seconds, self::SECONDS_PER_HOUR);
        $seconds %= self::SECONDS_PER_HOUR;

        $minutes = intdiv($seconds, self::SECONDS_PER_MINUTE);
        $seconds %= self::SECONDS_PER_MINUTE;

        $intervalSpec = sprintf(
            'P%dDT%dH%dM%dS',
            $days,
            $hours,
            $minutes,
            $seconds
        );

        $interval = new \DateInterval($intervalSpec);

        // Preserve negative durations using invert
        if ($this->totalSeconds < 0) {
            $interval->invert = 1;
        }

        return $interval;
    }

    public function jsonSerialize(): int
    {
        return $this->totalSeconds;
    }
}
