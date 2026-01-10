<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

final class TimeDelta implements \JsonSerializable
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
        $this->totalSeconds = $seconds;
    }

    public function isPositive(): bool
    {
        return $this->totalSeconds > 0;
    }

    public function isNegative(): bool
    {
        return $this->totalSeconds < 0;
    }

    public function invert(): self
    {
        return new self(-$this->totalSeconds);
    }

    public function sign(): int
    {
        return $this->totalSeconds <=> 0;
    }

    public function absolute(): DurationImmutable
    {
        return DurationImmutable::seconds(abs($this->totalSeconds));
    }
}
