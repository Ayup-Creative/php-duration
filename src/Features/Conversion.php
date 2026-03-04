<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Features;

use DateInterval;

trait Conversion
{
    /**
     * Get the total duration in seconds.
     *
     * @return int
     */
    public function toSeconds(): int
    {
        return $this->totalSeconds;
    }

    /**
     * Get the total duration in minutes.
     *
     * @return float
     */
    public function toMinutes(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_MINUTE;
    }

    /**
     * Get the total duration in hours.
     *
     * @return float
     */
    public function toHours(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_HOUR;
    }

    /**
     * Get the total duration in days.
     *
     * @return float
     */
    public function toDays(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_DAY;
    }

    /**
     * Get the total duration in weeks.
     *
     * @return float
     */
    public function toWeeks(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_WEEK;
    }

    /**
     * Get the total duration in months (approximate).
     *
     * @return float
     */
    public function toMonths(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_MONTH;
    }

    /**
     * Get the total duration in years (approximate).
     *
     * @return float
     */
    public function toYears(): float
    {
        return $this->totalSeconds / self::SECONDS_PER_YEAR;
    }

    /**
     * Convert the duration to a CarbonInterval instance.
     *
     * @return \Carbon\CarbonInterval
     */
    public function toCarbonInterval(): \Carbon\CarbonInterval
    {
        return \Carbon\CarbonInterval::seconds($this->totalSeconds);
    }

    /**
     * Convert the duration to a DateInterval instance.
     *
     * @return \DateInterval
     */
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

    /**
     * Serialize the duration to JSON.
     *
     * @return int
     */
    public function jsonSerialize(): int
    {
        return $this->totalSeconds;
    }
}
