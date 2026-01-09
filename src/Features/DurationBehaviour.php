<?php
declare(strict_types=1);

namespace AyupCreative\Duration\Features;

use AyupCreative\Duration\TimeDelta;
use Carbon\CarbonInterval;
use DateInterval;

/**
 * @property int $totalMinutes
 */
trait DurationBehaviour
{
    use Arithmetic;
    use Builders;
    use Formatting;
    use TemporalUnits;

    protected int $totalMinutes;

    public function __construct(int $minutes)
    {
        $this->totalMinutes = max(0, $minutes);
    }

    public function toDateInterval(): DateInterval
    {
        return new DateInterval('PT' . $this->totalMinutes . 'M');
    }

    public function jsonSerialize(): int
    {
        return $this->totalMinutes;
    }

    public function __get(string $name)
    {
        if($name === 'totalMinutes') {
            return $this->totalMinutes;
        }

        throw new \Error('Undefined property: ' . static::class . '::' . $name);
    }
}
