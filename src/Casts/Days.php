<?php

declare(strict_types=1);

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
