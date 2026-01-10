<?php

namespace AyupCreative\Duration\Casts;

final class Minutes extends DurationCast
{
    protected function getUnitsMethod(): string
    {
        return 'minutes';
    }
}
