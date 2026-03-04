<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

final class DurationImmutable implements \JsonSerializable, DurationInterface, Wireable
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
     * Create a new DurationImmutable instance.
     *
     * @param int $seconds
     * @see \AyupCreative\Duration\Tests\DurationImmutableTest::it_can_be_instantiated_with_seconds()
     */
    public function __construct(int $seconds)
    {
        $this->totalSeconds = max(0, $seconds);
    }

    /**
     * Convert the duration to a mutable instance.
     *
     * Usage: $mutable = $duration->toMutable();
     *
     * @return \AyupCreative\Duration\Duration
     */
    public function toMutable(): Duration
    {
        return Duration::seconds($this->totalSeconds);
    }
}
