<?php

namespace AyupCreative\Duration\Casts;

final class Seconds extends DurationCast
{
    protected function getUnitsMethod(): string
    {
        return 'seconds';
    }
}
