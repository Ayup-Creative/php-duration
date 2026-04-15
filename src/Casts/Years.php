<?php

declare(strict_types=1);

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
