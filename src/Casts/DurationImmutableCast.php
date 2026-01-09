<?php
declare(strict_types=1);

namespace AyupCreative\Duration\Casts;

use AyupCreative\Duration\TimeDelta;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use AyupCreative\Duration\DurationImmutable;
use InvalidArgumentException;

final class DurationImmutableCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): DurationImmutable
    {
        return DurationImmutable::minutes((int) $value);
    }

    public function set($model, string $key, $value, array $attributes): int
    {
        $debug = match(true) {
            is_scalar($value) => $value,
            is_object($value) => get_class($value),
            default => 'unknown',
        };

        return match (true) {
            $value instanceof DurationImmutable => $value->totalMinutes,
            $value instanceof TimeDelta => $value->absolute()->totalMinutes,
            is_int($value) => $value,
            default => throw new InvalidArgumentException('Invalid duration value ['.$debug.']'),
        };
    }
}
