<?php

namespace AyupCreative\Duration\Features;

/**
 * @property int $totalMinutes
 */
trait TemporalUnits
{
    public function totalMinutes(): int
    {
        return $this->totalMinutes;
    }

    public function getHours(): int
    {
        return intdiv($this->totalMinutes, 60);
    }

    public function getMinutes(): int
    {
        return $this->totalMinutes % 60;
    }
}
