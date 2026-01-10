<?php

namespace AyupCreative\Duration\Casts;

final class Days extends DurationCast
{
    /**
     * @inheritDoc
     */
    protected function getUnitsMethod(): string
    {
        return 'days';
    }
}
