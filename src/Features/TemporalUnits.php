<?php

namespace AyupCreative\Duration\Features;

trait TemporalUnits
{
    public function totalSeconds(): int
    {
        return $this->totalSeconds;
    }

    public function totalMinutes(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_MINUTE);
    }

    public function totalHours(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_HOUR);
    }

    public function totalDays(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_DAY);
    }

    public function totalWeeks(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_WEEK);
    }

    public function totalMonths(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_MONTH);
    }

    public function totalYears(): int
    {
        return intdiv($this->totalSeconds, self::SECONDS_PER_YEAR);
    }

    public function getHours(): int
    {
        return $this->decompose()['hours'] ?? 0;
    }

    public function getMinutes(): int
    {
        return $this->decompose()['minutes'] ?? 0;
    }

    public function getSeconds(): int
    {
        return $this->decompose()['seconds'] ?? 0;
    }
}
