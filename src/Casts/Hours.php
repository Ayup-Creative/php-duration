<?php

namespace AyupCreative\Duration\Casts;

final class Hours extends DurationCast
{
    protected function getUnitsMethod(): string
    {
        return 'hours';
    }
}
