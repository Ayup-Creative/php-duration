<?php

declare(strict_types=1);

namespace AyupCreative\Duration;

final class Duration implements \JsonSerializable
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

    public function add(DurationImmutable|self $other): self
    {
        $seconds = $this->totalSeconds + $other->totalSeconds;

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    public function sub(DurationImmutable|self $other): self
    {
        $seconds = $this->totalSeconds - $other->totalSeconds;

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    public function multiply(float $factor): self
    {
        $seconds = (int)round($this->totalSeconds * $factor);

        $this->totalSeconds = (new self($seconds))->totalSeconds;
        return $this;
    }

    public function ceilTo(int $seconds): self
    {
        $seconds = (int)(ceil($this->totalSeconds / $seconds) * $seconds);

        $this->totalSeconds = (new self($seconds))->totalSeconds;;
        return $this;
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

    public function toImmutable(): DurationImmutable
    {
        return DurationImmutable::seconds($this->totalSeconds);
    }
}
