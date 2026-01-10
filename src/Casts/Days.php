<?php

namespace AyupCreative\Duration\Casts;

final class Days extends DurationCast
{
    protected function getUnitsMethod(): string
    {
        return 'days';
    }
}
