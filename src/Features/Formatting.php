<?php

namespace AyupCreative\Duration\Features;

trait Formatting
{
    private const DEFAULT_FORMATTING_OPTIONS = [
        'spacer' => ' ',
        'units'  => true,
        'pad'    => null,
    ];

    /**
     * Format the duration using a format string.
     *
     * Available tokens:
     * *   : Sign (+ or -)
     * dd  : Days (padded to 2 digits)
     * d   : Days
     * hh  : Hours (padded to 2 digits)
     * h   : Hours
     * mm  : Minutes (padded to 2 digits)
     * m   : Minutes
     * ss  : Seconds (padded to 2 digits)
     * s   : Seconds
     *
     * Usage: $duration->format('dd:hh:mm:ss');
     *
     * @param string $format
     * @return string
     */
    public function format(string $format): string
    {
        $parts = $this->decompose();

        return strtr($format, [
            '*'  => $this->totalSeconds < 0 ? '-' : '',
            'dd' => str_pad((string) $parts['days'], 2, '0', STR_PAD_LEFT),
            'd'  => (string) $parts['days'],
            'hh' => str_pad((string) $parts['hours'], 2, '0', STR_PAD_LEFT),
            'h'  => (string) $parts['hours'],
            'mm' => str_pad((string) $parts['minutes'], 2, '0', STR_PAD_LEFT),
            'm'  => (string) $parts['minutes'],
            'ss' => str_pad((string) $parts['seconds'], 2, '0', STR_PAD_LEFT),
            's'  => (string) $parts['seconds'],
        ]);
    }

    /**
     * Get a human-readable representation of the duration.
     *
     * Usage: $duration->toHuman(); // "1 day 2 hours"
     *
     * @param callable|null $formatter Custom formatter function
     * @return string
     */
    public function toHuman(?callable $formatter = null): string {
        $parts = $this->decompose();

        if ($formatter !== null) {
            return $formatter($parts, $this);
        }

        return $this->defaultHuman($parts);
    }

    /**
     * Get a short human-readable representation of the duration.
     *
     * Usage: $duration->toShortHuman(); // "1d 2h 3m"
     *
     * @param callable|null $formatter Custom formatter function
     * @return string
     */
    public function toShortHuman(?callable $formatter = null): string
    {
        // Default short formatter
        $shortFormatter = function (array $parts) {
            $output = [];

            if ($parts['days'] > 0) {
                $output[] = $parts['days'] . 'd';
            }

            if ($parts['hours'] > 0) {
                $output[] = $parts['hours'] . 'h';
            }

            if ($parts['minutes'] > 0) {
                $output[] = $parts['minutes'] . 'm';
            }

            if ($parts['seconds'] > 0) {
                $output[] = $parts['seconds'] . 's';
            }

            if (empty($output)) {
                return ($parts['sign'] ?? '') . '0s';
            }

            return ($parts['sign'] ?? '') . implode(' ', $output);
        };

        // Use caller-provided formatter if supplied
        $formatter ??= $shortFormatter;

        // Call toHuman with the formatter
        return $this->toHuman($formatter);
    }

    /**
     * Converts time units into their respective values in seconds.
     *
     * @return null|int Number of seconds per unit.
     */
    protected static function unitSeconds(string $unit): ?int
    {
        $units = [
            'seconds' => 1,
            'minutes' => self::SECONDS_PER_MINUTE,
            'hours'   => self::SECONDS_PER_HOUR,
            'days'    => self::SECONDS_PER_DAY,
            'weeks'   => self::SECONDS_PER_WEEK,
            'years'   => self::SECONDS_PER_YEAR,
        ];

        return $units[$unit] ?? null;
    }

    /**
     * Formats a duration in terms of specified units.
     *
     * @param array $units A list of time units (e.g., ['hours', 'minutes', 'seconds']) to format the duration into.
     * @param array $options Additional formatting options.
     * @return string A string representation of the duration, formatted according to the specified units and options.
     * @throws \InvalidArgumentException If the units array is empty or contains an unrecognized unit.
     */
    public function formatUnits(array $units, array $options = []): string
    {
        if ($units === []) {
            throw new \InvalidArgumentException('At least one unit must be specified.');
        }

        $options = array_replace(self::DEFAULT_FORMATTING_OPTIONS, $options);

        $seconds = abs($this->totalSeconds);
        $sign = $this->totalSeconds < 0 ? '-' : '';

        $parts = [];

        foreach ($units as $unit) {
            if (self::unitSeconds($unit) === null) {
                throw new \InvalidArgumentException("Unknown unit [$unit].");
            }

            $unitSeconds = self::unitSeconds($unit);
            $value = intdiv($seconds, $unitSeconds);
            $seconds -= $value * $unitSeconds;

            $valueStr = $this->formatValue($value, $options['pad']);

            if ($options['units']) {
                $valueStr .= $this->unitSuffix($unit);
            }

            $parts[] = $valueStr;
        }

        return $sign . implode($options['spacer'], $parts);
    }

    /**
     * Formats an integer value as a string, optionally padding it with leading zeros.
     *
     * @param int $value The integer value to format.
     * @param int|null $pad The total width of the resulting string. If null, no padding is applied.
     * @return string The formatted string with optional padding.
     */
    private function formatValue(int $value, ?int $pad): string
    {
        if ($pad === null) {
            return (string) $value;
        }

        return str_pad((string) $value, $pad, '0', STR_PAD_LEFT);
    }

    /**
     * Converts a time unit to its corresponding suffix.
     *
     * @param string $unit The name of the time unit (e.g., 'seconds', 'minutes', etc.).
     * @return string The shortened suffix for the provided time unit. If no match is found, the original unit string is returned.
     */
    private function unitSuffix(string $unit): string
    {
        return match ($unit) {
            'seconds' => 's',
            'minutes' => 'm',
            'hours'   => 'h',
            'days'    => 'd',
            'weeks'   => 'w',
            'years'   => 'y',
            default   => $unit,
        };
    }

    /**
     * Convert the duration to a string (hh:mm).
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->format('*hh:mm');
    }

    /**
     * Default human-readable formatter.
     *
     * @param array $parts
     * @return string
     */
    private function defaultHuman(array $parts): string
    {
        $output = [];

        if ($parts['days'] > 0) {
            $output[] = $parts['days'] . ' ' . $this->pluralize($parts['days'], 'day');
        }

        if ($parts['hours'] > 0) {
             $output[] = $parts['hours'] . ' ' . $this->pluralize($parts['hours'], 'hour');
        }

        if ($parts['minutes'] > 0) {
            $output[] = $parts['minutes'] . ' ' . $this->pluralize($parts['minutes'], 'minute');
        }

        if ($parts['seconds'] > 0) {
            $output[] = $parts['seconds'] . ' ' . $this->pluralize($parts['seconds'], 'second');
        }

        if (empty($output)) {
            return $parts['sign'] . '0 seconds';
        }

        $humanParts = array_slice($output, 0, 2);

        return $parts['sign'] . implode(' ', $humanParts);
    }

    /**
     * Pluralize a label based on a value.
     *
     * @param int $value
     * @param string $label
     * @return string
     */
    private function pluralize(int $value, string $label): string
    {
        return $value === 1 ? $label : $label . 's';
    }
}
