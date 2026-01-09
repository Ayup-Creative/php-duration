<?php
declare(strict_types=1);

namespace AyupCreative\Duration;

use AyupCreative\Duration\Features\DurationBehaviour;
use Carbon\CarbonInterval;

final class DurationImmutable implements \JsonSerializable
{
    use DurationBehaviour;

    public function add(self $other): self
    {
        return new self($this->totalMinutes + $other->totalMinutes);
    }

    public function sub(self $other): self
    {
        return new self($this->totalMinutes - $other->totalMinutes);
    }

    public function multiply(float $factor): self
    {
        return new self((int) round($this->totalMinutes * $factor));
    }

    public function ceilTo(int $interval): self
    {
        return new self(
            (int) (ceil($this->totalMinutes / $interval) * $interval)
        );
    }

    public function toMutable(): Duration
    {
        return Duration::minutes($this->totalMinutes);
    }
}
