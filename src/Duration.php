<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

final class Duration implements \JsonSerializable, DurationInterface, Wireable
{
    use Features\Arithmetic;
    use Features\Builders;
    use Features\Constants;
    use Features\Conversion;
    use Features\Formatting;
    use Features\MagicProperties;
    use Features\TemporalUnits;
    use Features\Wireable;

    protected int $totalSeconds;

    /**
     * Create a new Duration instance.
     *
     * @param int $seconds
     * @see \AyupCreative\Duration\Tests\DurationTest::it_can_be_instantiated_with_seconds()
     */
    public function __construct(int $seconds)
    {
        $this->totalSeconds = max(0, $seconds);
    }

    /**
     * Add another duration to the current one.
     *
     * Usage: $duration->add(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return self
     * @see \AyupCreative\Duration\Tests\DurationTest::it_is_mutable_on_arithmetic_operations()
     */
    public function add(DurationInterface $other): self
    {
        $seconds = $this->totalSeconds + $other->totalSeconds();

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    /**
     * Subtract another duration from the current one.
     *
     * Usage: $duration->sub(Duration::minutes(5));
     *
     * @param \AyupCreative\Duration\DurationInterface $other
     * @return self
     * @see \AyupCreative\Duration\Tests\DurationTest::it_is_mutable_on_arithmetic_operations()
     */
    public function sub(DurationInterface $other): self
    {
        $seconds = $this->totalSeconds - $other->totalSeconds();

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    /**
     * Multiply the duration by a factor.
     *
     * Usage: $duration->multiply(1.5);
     *
     * @param float $factor
     * @return self
     * @see \AyupCreative\Duration\Tests\DurationTest::it_is_mutable_on_arithmetic_operations()
     */
    public function multiply(float $factor): self
    {
        $seconds = (int)round($this->totalSeconds * $factor);

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    /**
     * Ceil the duration to the nearest multiple of the given seconds.
     *
     * Usage: $duration->ceilTo(30);
     *
     * @param int $seconds
     * @return self
     * @see \AyupCreative\Duration\Tests\DurationTest::it_can_ceil_durations()
     */
    public function ceilTo(int $seconds): self
    {
        if ($seconds === 0) {
            return $this;
        }

        $seconds = (int)(ceil($this->totalSeconds / $seconds) * $seconds);

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    /**
     * Ceil the duration to the nearest multiple of the given minutes.
     *
     * Usage: $duration->ceilToMinutes(15);
     *
     * @param int $minutes
     * @return self
     * @see \AyupCreative\Duration\Tests\DurationTest::it_can_ceil_durations()
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
     * @see \AyupCreative\Duration\Tests\DurationTest::it_can_ceil_durations()
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
     * @see \AyupCreative\Duration\Tests\DurationTest::it_can_ceil_durations()
     */
    public function ceilToDays(int $days): self
    {
        return $this->ceilTo($days * self::SECONDS_PER_DAY);
    }

    /**
     * Convert the duration to an immutable instance.
     *
     * Usage: $immutable = $duration->toImmutable();
     *
     * @return \AyupCreative\Duration\DurationImmutable
     * @see \AyupCreative\Duration\Tests\DurationTest::it_can_be_converted_to_immutable()
     */
    public function toImmutable(): DurationImmutable
    {
        return DurationImmutable::seconds($this->totalSeconds);
    }
}
