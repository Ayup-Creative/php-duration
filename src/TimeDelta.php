<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

final class TimeDelta implements \JsonSerializable, DurationInterface
{
    use Features\Arithmetic;
    use Features\Builders;
    use Features\Constants;
    use Features\Conversion;
    use Features\Formatting;
    use Features\MagicProperties;
    use Features\TemporalUnits;

    protected int $totalSeconds;

    /**
     * Create a new TimeDelta instance.
     *
     * @param int $seconds
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_be_instantiated_with_positive_seconds()
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_be_instantiated_with_negative_seconds()
     */
    public function __construct(int $seconds)
    {
        $this->totalSeconds = $seconds;
    }

    /**
     * Check if the duration is positive.
     *
     * Usage: $isPositive = $delta->isPositive();
     *
     * @return bool
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_be_instantiated_with_positive_seconds()
     */
    public function isPositive(): bool
    {
        return $this->totalSeconds > 0;
    }

    /**
     * Check if the duration is negative.
     *
     * Usage: $isNegative = $delta->isNegative();
     *
     * @return bool
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_be_instantiated_with_negative_seconds()
     */
    public function isNegative(): bool
    {
        return $this->totalSeconds < 0;
    }

    /**
     * Invert the sign of the duration.
     *
     * Usage: $inverted = $delta->invert();
     *
     * @return self
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_invert_its_value()
     */
    public function invert(): self
    {
        return new self(-$this->totalSeconds);
    }

    /**
     * Get the sign of the duration (-1, 0, or 1).
     *
     * Usage: $sign = $delta->sign();
     *
     * @return int
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_be_instantiated_with_positive_seconds()
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_be_instantiated_with_negative_seconds()
     */
    public function sign(): int
    {
        return $this->totalSeconds <=> 0;
    }

    /**
     * Get the absolute duration as a DurationImmutable instance.
     *
     * Usage: $abs = $delta->absolute();
     *
     * @return \AyupCreative\Duration\DurationImmutable
     * @see \AyupCreative\Duration\Tests\TimeDeltaTest::it_can_return_absolute_duration()
     */
    public function absolute(): DurationImmutable
    {
        return DurationImmutable::seconds(abs($this->totalSeconds));
    }
}
