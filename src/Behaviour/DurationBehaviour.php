<?php
declare(strict_types=1);

namespace AyupCreative\Duration\Behaviour;

use AyupCreative\Duration\TimeDelta;
use Carbon\CarbonInterval;
use DateInterval;

/**
 * @property int $totalMinutes
 */
trait DurationBehaviour
{
    protected int $totalMinutes;

    protected string $format = '%d:%02d';

    public function __construct(int $minutes)
    {
        $this->totalMinutes = max(0, $minutes);
    }

    public static function minutes(int $minutes): self
    {
        return new self($minutes);
    }

    public static function hours(int $hours): self
    {
        return new self($hours * 60);
    }

    public static function hoursAndMinutes(int $hours, int $minutes): self
    {
        return new self(($hours * 60) + $minutes);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public static function fromCarbon(CarbonInterval $interval): self
    {
        return new self((int) $interval->totalMinutes);
    }

    public function getHours(): int
    {
        return intdiv($this->totalMinutes, 60);
    }

    public function remainderMinutes(): int
    {
        return $this->totalMinutes % 60;
    }

    public function isOver(self $other): bool
    {
        return $this->totalMinutes > $other->totalMinutes;
    }

    public function isBelow(self $other): bool
    {
        return $this->totalMinutes < $other->totalMinutes;
    }

    public function equals(self $other): bool
    {
        return $this->totalMinutes === $other->totalMinutes;
    }

    public function isZero(): bool
    {
        return $this->totalMinutes === 0;
    }

    public function max(self $other): self
    {
        return $this->totalMinutes >= $other->totalMinutes ? $this : $other;
    }

    public function min(self $other): self
    {
        return $this->totalMinutes <= $other->totalMinutes ? $this : $other;
    }

    public function diff(self $other): TimeDelta
    {
        return TimeDelta::minutes(
            $this->totalMinutes - $other->totalMinutes
        );
    }

    public function toDateInterval(): DateInterval
    {
        return new DateInterval('PT' . $this->totalMinutes . 'M');
    }

    public function toHuman(): string
    {
        return match (true) {
            $this->totalMinutes < 60 => "{$this->totalMinutes} minutes",
            $this->totalMinutes % 60 === 0 => ($this->totalMinutes / 60) . " hours",
            default => sprintf(
                '%d hours %d minutes',
                $this->getHours(),
                $this->remainderMinutes()
            ),
        };
    }


    public function jsonSerialize(): int
    {
        return $this->totalMinutes;
    }

    public function format(string $format): string
    {
        return sprintf($format, $this->getHours(), $this->remainderMinutes());
    }


    public function __toString(): string
    {
        return $this->format($this->format);
    }

    public function __get(string $name)
    {
        if($name === 'totalMinutes') {
            return $this->totalMinutes;
        }

        throw new \Error('Undefined property: ' . static::class . '::' . $name);
    }
}
