<?php

namespace AyupCreative\Duration\Features;

use AyupCreative\Duration\TimeDelta;

/**
 * @property int $totalSeconds
 */
trait Arithmetic
{
    public function add(self $other): self
    {
        return new self($this->totalSeconds + $other->totalSeconds);
    }

    public function sub(self $other): self
    {
        return new self($this->totalSeconds - $other->totalSeconds);
    }

    public function multiply(float $factor): self
    {
        return new self((int) round($this->totalSeconds * $factor));
    }

    public function ceilTo(int $seconds): self
    {
        return new self(
            (int) (ceil($this->totalSeconds / $seconds) * $seconds)
        );
    }

    public function ceilToMinutes(int $minutes): self
    {
        return $this->ceilTo($minutes * self::SECONDS_PER_MINUTE);
    }

    public function ceilToHours(int $hours): self
    {
        return $this->ceilTo($hours * self::SECONDS_PER_HOUR);
    }

    public function ceilToDays(int $days): self
    {
        return $this->ceilTo($days * self::SECONDS_PER_DAY);
    }

    public function isOver(self $other): bool
    {
        return $this->totalSeconds > $other->totalSeconds;
    }

    public function isBelow(self $other): bool
    {
        return $this->totalSeconds < $other->totalSeconds;
    }

    public function isLessThan(self $other): bool
    {
        return $this->isBelow($other);
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->isOver($other);
    }

    public function isLessThanOrEqualTo(self $other): bool
    {
        return $this->isBelow($other) || $this->equals($other);
    }

    public function isGreaterThanOrEqualTo(self $other): bool
    {
        return $this->isOver($other) || $this->equals($other);
    }

    public function equals(self $other): bool
    {
        return $this->totalSeconds === $other->totalSeconds;
    }

    public function doesNotEqual(self $other): bool
    {
        return !$this->equals($other);
    }

    public function isZero(): bool
    {
        return $this->totalSeconds === 0;
    }

    public function isNotZero(): bool
    {
        return $this->totalSeconds !== 0;
    }

    public function max(self $other): self
    {
        return $this->totalSeconds >= $other->totalSeconds ? $this : $other;
    }

    public function min(self $other): self
    {
        return $this->totalSeconds <= $other->totalSeconds ? $this : $other;
    }

    public function diff(self $other): TimeDelta
    {
        return TimeDelta::seconds(
            $this->totalSeconds - $other->totalSeconds
        );
    }
}
