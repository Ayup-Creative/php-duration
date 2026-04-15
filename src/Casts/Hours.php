<?php

declare(strict_types=1);

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
