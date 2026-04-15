<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Features;

use AyupCreative\Duration\DurationInterface;
use AyupCreative\Duration\TimeDelta;

/**
 * @property int $totalSeconds
 */
trait Arithmetic
{
    /**
     * Add another duration.
     *
     * Usage: $duration->add(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return self
     */
    public function add(DurationInterface $other): self
    {
        return new self($this->totalSeconds + $other->totalSeconds());
    }

    /**
     * Subtract another duration.
     *
     * Usage: $duration->sub(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return self
     */
    public function sub(DurationInterface $other): self
    {
        return new self($this->totalSeconds - $other->totalSeconds());
    }

    /**
     * Multiply the duration by a factor.
     *
     * Usage: $duration->multiply(1.5);
     *
     * @param float $factor
     * @return self
     */
    public function multiply(float $factor): self
    {
        return new self((int) round($this->totalSeconds * $factor));
    }

    /**
     * Ceil the duration to the nearest multiple of the given seconds.
     *
     * Usage: $duration->ceilTo(30);
     *
     * @param int $seconds
     * @return self
     */
    public function ceilTo(int $seconds): self
    {
        if ($seconds === 0) {
            return $this;
        }

        return new self(
            (int) (ceil($this->totalSeconds / $seconds) * $seconds)
        );
    }

    /**
     * Ceil the duration to the nearest multiple of the given minutes.
     *
     * Usage: $duration->ceilToMinutes(15);
     *
     * @param int $minutes
     * @return self
     */
    public function ceilToMinutes(int $minutes): self
    {
        return $this->ceilTo($minutes * self::SECONDS_PER_MINUTE);
    }

    /**
     * Ceil the duration to the nearest multiple of the given hours.
     *
     * Usage: $duration->ceilToHours(1);
     *
     * @param int $hours
     * @return self
     */
    public function ceilToHours(int $hours): self
    {
        return $this->ceilTo($hours * self::SECONDS_PER_HOUR);
    }

    /**
     * Ceil the duration to the nearest multiple of the given days.
     *
     * Usage: $duration->ceilToDays(1);
     *
     * @param int $days
     * @return self
     */
    public function ceilToDays(int $days): self
    {
        return $this->ceilTo($days * self::SECONDS_PER_DAY);
    }

    /**
     * Check if the duration is greater than another.
     *
     * Usage: $duration->isOver(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function isOver(DurationInterface $other): bool
    {
        return $this->totalSeconds > $other->totalSeconds();
    }

    /**
     * Check if the duration is less than another.
     *
     * Usage: $duration->isBelow(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function isBelow(DurationInterface $other): bool
    {
        return $this->totalSeconds < $other->totalSeconds();
    }

    /**
     * Check if the duration is less than another.
     *
     * Usage: $duration->isLessThan(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function isLessThan(DurationInterface $other): bool
    {
        return $this->isBelow($other);
    }

    /**
     * Check if the duration is greater than another.
     *
     * Usage: $duration->isGreaterThan(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function isGreaterThan(DurationInterface $other): bool
    {
        return $this->isOver($other);
    }

    /**
     * Check if the duration is less than or equal to another.
     *
     * Usage: $duration->isLessThanOrEqualTo(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function isLessThanOrEqualTo(DurationInterface $other): bool
    {
        return $this->isBelow($other) || $this->equals($other);
    }

    /**
     * Check if the duration is greater than or equal to another.
     *
     * Usage: $duration->isGreaterThanOrEqualTo(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function isGreaterThanOrEqualTo(DurationInterface $other): bool
    {
        return $this->isOver($other) || $this->equals($other);
    }

    /**
     * Check if the duration is equal to another.
     *
     * Usage: $duration->equals(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function equals(DurationInterface $other): bool
    {
        return $this->totalSeconds === $other->totalSeconds();
    }

    /**
     * Check if the duration does not equal another.
     *
     * Usage: $duration->doesNotEqual(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return bool
     */
    public function doesNotEqual(DurationInterface $other): bool
    {
        return !$this->equals($other);
    }

    /**
     * Check if the duration is zero.
     *
     * Usage: $duration->isZero();
     *
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->totalSeconds === 0;
    }

    /**
     * Check if the duration is not zero.
     *
     * Usage: $duration->isNotZero();
     *
     * @return bool
     */
    public function isNotZero(): bool
    {
        return $this->totalSeconds !== 0;
    }

    /**
     * Get the maximum of two durations.
     *
     * Usage: $max = $duration->max(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return self
     */
    public function max(DurationInterface $other): self
    {
        if ($this->totalSeconds >= $other->totalSeconds()) {
            return $this;
        }

        return $other instanceof self ? $other : new self($other->totalSeconds());
    }

    /**
     * Get the minimum of two durations.
     *
     * Usage: $min = $duration->min(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return self
     */
    public function min(DurationInterface $other): self
    {
        if ($this->totalSeconds <= $other->totalSeconds()) {
            return $this;
        }

        return $other instanceof self ? $other : new self($other->totalSeconds());
    }

    /**
     * Get the difference between two durations.
     *
     * Usage: $delta = $duration->diff(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return \AyupCreative\Duration\TimeDelta
     */
    public function diff(DurationInterface $other): TimeDelta
    {
        return TimeDelta::seconds(
            $this->totalSeconds - $other->totalSeconds()
        );
    }
}
