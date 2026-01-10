<?php

namespace AyupCreative\Duration\Features;

trait Formatting
{
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

    public function toHuman(?callable $formatter = null): string {
        $parts = $this->decompose();

        if ($formatter !== null) {
            return $formatter($parts, $this);
        }

        return $this->defaultHuman($parts);
    }

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

    public function __toString(): string
    {
        return $this->format('*hh:mm');
    }

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

    private function pluralize(int $value, string $label): string
    {
        return $value === 1 ? $label : $label . 's';
    }
}
