<?php

namespace AyupCreative\Duration\Features;

trait Formatting
{
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

            // Only include seconds if non-zero (optional)
            if ($parts['seconds'] > 0 && empty($output)) {
                $output[] = $parts['seconds'] . 's';
            }

            return ($parts['sign'] ?? '') . implode(' ', $output);
        };

        // Use caller-provided formatter if supplied
        $formatter ??= $shortFormatter;

        // Call toHuman with the formatter
        return $this->toHuman($formatter);
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
        if (abs($this->totalSeconds) < self::SECONDS_PER_MINUTE) {
            return $parts['sign'] . $parts['seconds'] . ' ' . $this->pluralize($parts['seconds'], 'second');
        }

        if ($parts['days'] > 0) {
            return sprintf(
                '%s%d%s%s %d%s%s',
                $parts['sign'],
                $parts['days'],
                ' ',
                $this->pluralize($parts['days'], 'day'),
                $parts['hours'],
                ' ',
                $this->pluralize($parts['hours'], 'hour')
            );
        }

        return sprintf(
            '%s%d%s%s %d%s%s',
            $parts['sign'],
            $parts['hours'],
            ' ',
            $this->pluralize($parts['hours'], 'hour'),
            $parts['minutes'],
            ' ',
            $this->pluralize($parts['minutes'], 'minute')
        );
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
