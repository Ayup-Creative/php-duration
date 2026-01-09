<?php

namespace AyupCreative\Duration\Features;

use AyupCreative\Duration\TimeDelta;

/**
 * @property int $totalMinutes
 */
trait Arithmetic
{
    public function add(self $other): self
    {
        return new self($this->totalMinutes + $other->totalMinutes);
    }

    public function sub(self $other): self
    {
        return new self($this->totalMinutes - $other->totalMinutes);
    }

    public function multiply(float $factor): self
    {
        return new self((int) round($this->totalMinutes * $factor));
    }

    public function ceilTo(int $interval): self
    {
        return new self(
            (int) (ceil($this->totalMinutes / $interval) * $interval)
        );
    }

    public function isOver(self $other): bool
    {
        return $this->totalMinutes > $other->totalMinutes;
    }

    public function isBelow(self $other): bool
    {
        return $this->totalMinutes < $other->totalMinutes;
    }

    public function equals(self $other): bool
    {
        return $this->totalMinutes === $other->totalMinutes;
    }

    public function isZero(): bool
    {
        return $this->totalMinutes === 0;
    }

    public function max(self $other): self
    {
        return $this->totalMinutes >= $other->totalMinutes ? $this : $other;
    }

    public function min(self $other): self
    {
        return $this->totalMinutes <= $other->totalMinutes ? $this : $other;
    }

    public function diff(self $other): TimeDelta
    {
        return TimeDelta::minutes(
            $this->totalMinutes - $other->totalMinutes
        );
    }
}
