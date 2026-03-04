<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Features;

trait Wireable
{
    /**
     * Get the value that should be stored by Livewire.
     *
     * @return int
     */
    public function toLivewire(): int
    {
        return $this->totalSeconds();
    }

    /**
     * Create an instance from the value stored by Livewire.
     *
     * @param int|string $value
     * @return static
     */
    public static function fromLivewire($value): static
    {
        return static::seconds((int) $value);
    }
}
