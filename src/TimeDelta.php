<?php

namespace AyupCreative\Duration;

use AyupCreative\Duration\Features\Arithmetic;
use AyupCreative\Duration\Features\Builders;
use AyupCreative\Duration\Features\Formatting;
use AyupCreative\Duration\Features\TemporalUnits;

/**
 * @property int $totalMinutes
 */
final class TimeDelta
{
    use Arithmetic;
    use Builders;
    use Formatting;
    use TemporalUnits;

    private int $totalMinutes;

    private function __construct(int $minutes)
    {
        $this->totalMinutes = $minutes;
    }

    public function isPositive(): bool
    {
        return $this->totalMinutes > 0;
    }

    public function isNegative(): bool
    {
        return $this->totalMinutes < 0;
    }

    public function invert(): self
    {
        return new self(-$this->totalMinutes);
    }

    public function sign(): int
    {
        return $this->totalMinutes <=> 0;
    }

    public function absolute(): DurationImmutable
    {
        return DurationImmutable::minutes(abs($this->totalMinutes));
    }

    public function __get($name)
    {
        if($name === 'totalMinutes') {
            return $this->totalMinutes;
        }

        throw new \Error('Undefined property: ' . static::class . '::' . $name);
    }
}
