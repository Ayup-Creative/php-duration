<?php

namespace AyupCreative\Duration\Features;

trait Formatting
{
    public function format(string $format): string
    {
        $hours = abs($this->getHours());
        $rMinutes = abs($this->getMinutes());
        $minutes = abs($this->totalMinutes());

        return strtr($format, [
            '*' => $this->totalMinutes < 0 ? '-' : '',
            'hh' => str_pad((string)$hours, 2, '0', STR_PAD_LEFT),
            'h'  => (string)$hours,
            'mm' => str_pad((string)$rMinutes, 2, '0', STR_PAD_LEFT),
            'm'  => (string)$rMinutes,
            't' => (string)$minutes,
            'tt' => str_pad((string)$minutes, 2, '0', STR_PAD_LEFT),
        ]);
    }

    public function toHuman(string $hours = 'hours', string $minutes = 'minutes', string $spacer = ' '): string
    {
        return match (true) {
            $this->totalMinutes < 60 => "{$this->totalMinutes}{$spacer}{$minutes}",
            $this->totalMinutes % 60 === 0 => ($this->totalMinutes / 60) . "{$spacer}{$hours}",
            default => sprintf(
                '%d%s%s %d%s%s',
                $this->getHours(),
                $spacer,
                $hours,
                $this->getMinutes(),
                $spacer,
                $minutes
            ),
        };
    }

    public function toShortHuman(string $hours = 'hrs', string $minutes = 'm', string $spacer = ''): string
    {
        return $this->toHuman($hours, $minutes, $spacer);
    }

    public function __toString(): string
    {
        return $this->format('*hh:mm');
    }
}
