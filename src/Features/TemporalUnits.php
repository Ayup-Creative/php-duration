<?php

namespace AyupCreative\Duration\Features;

trait TemporalUnits
{
    /**
     * Get the total duration in seconds.
     *
     * @return int
     */
    public function totalSeconds(): int
    {
        return $this->totalSeconds;
    }

    /**
     * Get the total duration in whole minutes.
     *
     * @return int
     */
    public function totalMinutes(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_MINUTE);
    }

    /**
     * Get the total duration in whole hours.
     *
     * @return int
     */
    public function totalHours(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_HOUR);
    }

    /**
     * Get the total duration in whole days.
     *
     * @return int
     */
    public function totalDays(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_DAY);
    }

    /**
     * Get the total duration in whole weeks.
     *
     * @return int
     */
    public function totalWeeks(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_WEEK);
    }

    /**
     * Get the total duration in whole months (approximate).
     *
     * @return int
     */
    public function totalMonths(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_MONTH);
    }

    /**
     * Get the total duration in whole years (approximate).
     *
     * @return int
     */
    public function totalYears(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_YEAR);
    }

    /**
     * Get the hours part of the decomposed duration.
     *
     * @return int
     */
    public function getHours(): int
    {
        return $this->decompose()['hours'] ?? 0;
    }

    /**
     * Get the minutes part of the decomposed duration.
     *
     * @return int
     */
    public function getMinutes(): int
    {
        return $this->decompose()['minutes'] ?? 0;
    }

    /**
     * Get the seconds part of the decomposed duration.
     *
     * @return int
     */
    public function getSeconds(): int
    {
        return $this->decompose()['seconds'] ?? 0;
    }
}
