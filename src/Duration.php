<?php
declare(strict_types=1);

namespace AyupCreative\Duration;

use AyupCreative\Duration\Features\DurationBehaviour;

final class Duration implements \JsonSerializable
{
    use DurationBehaviour;

    public function add(DurationImmutable|self $other): self
    {
        $this->totalMinutes += $other->totalMinutes;
        return $this;
    }

    public function sub(DurationImmutable|self $other): self
    {
        $this->totalMinutes = max(0, $this->totalMinutes - $other->totalMinutes);
        return $this;
    }

    public function multiply(float $factor): self
    {
        $this->totalMinutes = (int) round($this->totalMinutes * $factor);
        return $this;
    }

    public function ceilTo(int $interval): self
    {
        $this->totalMinutes = (int) (ceil($this->totalMinutes / $interval) * $interval);
        return $this;
    }

    public function toImmutable(): DurationImmutable
    {
        return DurationImmutable::minutes($this->totalMinutes);
    }
}
