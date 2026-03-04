<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Features;

trait Constants
{
    public const SECONDS_PER_MINUTE = 60;
    public const SECONDS_PER_HOUR  = 3600;
    public const SECONDS_PER_DAY   = 86400;
    public const SECONDS_PER_WEEK  = 604800;
    public const SECONDS_PER_MONTH = 2629800; // 30.44 days
    public const SECONDS_PER_YEAR  = 31536000; // 365 days

    /**
     * Decompose the duration into days, hours, minutes, and seconds.
     *
     * @return array{days: int, hours: int, minutes: int, seconds: int, sign: string}
     */
    private function decompose(): array
    {
        $seconds = abs($this->totalSeconds);

        $days = intdiv($seconds, self::SECONDS_PER_DAY);
        $seconds %= self::SECONDS_PER_DAY;

        $hours = intdiv($seconds, self::SECONDS_PER_HOUR);
        $seconds %= self::SECONDS_PER_HOUR;

        $minutes = intdiv($seconds, self::SECONDS_PER_MINUTE);
        $seconds %= self::SECONDS_PER_MINUTE;

        return [
            'days'    => $days,
            'hours'   => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'sign' => $this->totalSeconds < 0 ? '-' : ''
        ];
    }
}
