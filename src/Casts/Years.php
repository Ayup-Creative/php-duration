<?php

namespace AyupCreative\Duration\Casts;

final class Years extends DurationCast
{
    /**
     * @inheritDoc
     */
    protected function getUnitsMethod(): string
    {
        return 'years';
    }
}
