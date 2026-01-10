<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

final class DurationImmutable implements \JsonSerializable
{
    use Features\Arithmetic;
    use Features\Builders;
    use Features\Constants;
    use Features\Conversion;
    use Features\Formatting;
    use Features\MagicProperties;
    use Features\TemporalUnits;

    protected int $totalSeconds;

    public function __construct(int $seconds)
    {
        $this->totalSeconds = max(0, $seconds);
    }

    public function toMutable(): Duration
    {
        return Duration::seconds($this->totalSeconds);
    }
}
