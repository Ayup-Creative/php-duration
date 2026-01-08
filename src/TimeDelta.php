<?php

namespace AyupCreative\Duration;

/**
 * @property int $totalMinutes
 */
final class TimeDelta
{
    private int $minutes;

    private function __construct(int $minutes)
    {
        $this->minutes = $minutes;
    }

    public static function minutes(int $minutes): self
    {
        return new self($minutes);
    }

    public function totalMinutes(): int
    {
        return $this->minutes;
    }

    public function isPositive(): bool
    {
        return $this->minutes > 0;
    }

    public function isNegative(): bool
    {
        return $this->minutes < 0;
    }

    public function invert(): self
    {
        return new self(-$this->minutes);
    }

    public function sign(): int
    {
        return $this->minutes <=> 0;
    }

    public function absolute(): DurationImmutable
    {
        return DurationImmutable::minutes(abs($this->minutes));
    }

    public function __toString(): string
    {
        $sign = $this->minutes < 0 ? '-' : '';
        $m = abs($this->minutes);

        return sprintf(
            '%s%d:%02d',
            $sign,
            intdiv($m, 60),
            $m % 60
        );
    }

    public function __get($name)
    {
        if($name === 'totalMinutes') {
            return $this->minutes;
        }

        throw new \Error('Undefined property: ' . static::class . '::' . $name);
    }
}
