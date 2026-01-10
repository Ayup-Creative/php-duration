<?php

namespace AyupCreative\Duration\Casts;

final class Hours extends DurationCast
{
    /**
     * @inheritDoc
     */
    protected function getUnitsMethod(): string
    {
        return 'hours';
    }
}
