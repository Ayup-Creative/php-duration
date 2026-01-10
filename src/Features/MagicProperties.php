<?php

namespace AyupCreative\Duration\Features;

/**
 * @property int $totalSeconds
 * @property int $totalMinutes
 * @property int $totalHours
 * @property int $totalDays
 * @property int $totalWeeks
 * @property int $totalMonths
 * @property int $totalYears
 */
trait MagicProperties
{
    /**
     * Magic getter for total units.
     *
     * @param string $name
     * @return mixed
     * @throws \Error
     */
    public function __get($name)
    {
        if($name === 'totalSeconds') {
            return $this->totalSeconds();
        }

        if($name === 'totalMinutes') {
            return $this->totalMinutes();
        }

        if($name === 'totalHours') {
            return $this->totalHours();
        }

        if($name === 'totalDays') {
            return $this->totalDays();
        }

        if($name === 'totalWeeks') {
            return $this->totalWeeks();
        }

        if($name === 'totalMonths') {
            return $this->totalMonths();
        }

        if($name === 'totalYears') {
            return $this->totalYears();
        }

        throw new \Error('Undefined property: ' . self::class . '::' . $name);
    }

    /**
     * Magic setter to prevent setting properties.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws \Error
     */
    public function __set($name, $value)
    {
        throw new \Error('Cannot set property: ' . self::class . '::' . $name);
    }
}
