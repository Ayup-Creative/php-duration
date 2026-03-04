<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Features;

use Carbon\CarbonInterval;

trait Builders
{
    /**
     * Create a duration of zero.
     *
     * @return self
     */
    public static function zero(): self
    {
        return new self(0);
    }

    /**
     * Create a duration from seconds.
     *
     * @param int $seconds
     * @return self
     */
    public static function seconds(int $seconds): self
    {
        return new self($seconds);
    }

    /**
     * Create a duration from minutes.
     *
     * @param int $minutes
     * @return self
     */
    public static function minutes(int $minutes): self
    {
        return new self($minutes * self::SECONDS_PER_MINUTE);
    }

    /**
     * Create a duration from hours.
     *
     * @param int $hours
     * @return self
     */
    public static function hours(int $hours): self
    {
        return new self($hours * self::SECONDS_PER_HOUR);
    }

    /**
     * Create a duration from days.
     *
     * @param int $days
     * @return self
     */
    public static function days(int $days): self
    {
        return new self($days * self::SECONDS_PER_DAY);
    }

    /**
     * Create a duration from weeks.
     *
     * @param int $weeks
     * @return self
     */
    public static function weeks(int $weeks): self
    {
        return new self($weeks * self::SECONDS_PER_WEEK);
    }

    /**
     * Create a duration from months (approximate).
     *
     * @param int $months
     * @return self
     */
    public static function months(int $months): self
    {
        return new self($months * self::SECONDS_PER_MONTH);
    }

    /**
     * Create a duration from years (approximate).
     *
     * @param int $years
     * @return self
     */
    public static function years(int $years): self
    {
        return new self($years * self::SECONDS_PER_YEAR);
    }

    /**
     * Create a duration from hours and minutes.
     *
     * @param int $hours
     * @param int $minutes
     * @return self
     */
    public static function hoursAndMinutes(int $hours, int $minutes): self
    {
        return new self(($hours * self::SECONDS_PER_HOUR) + ($minutes * self::SECONDS_PER_MINUTE));
    }

    /**
     * Create a duration from various units.
     *
     * @param int $days
     * @param int $hours
     * @param int $minutes
     * @param int $seconds
     * @return self
     */
    public static function make(int $days = 0, int $hours = 0, int $minutes = 0, int $seconds = 0): self
    {
        $seconds += ($hours * self::SECONDS_PER_HOUR) +
            ($minutes * self::SECONDS_PER_MINUTE) +
            ($days * self::SECONDS_PER_DAY);

        return new self($seconds);
    }

    /**
     * Create a duration from a CarbonInterval.
     *
     * @param \Carbon\CarbonInterval $interval
     * @return self
     */
    public static function fromCarbon(CarbonInterval $interval): self
    {
        return new self((int) $interval->totalSeconds);
    }
}
