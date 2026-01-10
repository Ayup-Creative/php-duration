<?php

namespace AyupCreative\Duration\Casts;

final class Seconds extends DurationCast
{
    /**
     * @inheritDoc
     */
    protected function getUnitsMethod(): string
    {
        return 'seconds';
    }
}
