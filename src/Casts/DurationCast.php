<?php
declare(strict_types=1);

namespace AyupCreative\Duration\Casts;

use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

abstract class DurationCast implements CastsAttributes
{
    abstract protected function getUnitsMethod(): string;

    public function get($model, string $key, $value, array $attributes): DurationImmutable
    {
        if(!method_exists(DurationImmutable::class, $this->getUnitsMethod())) {
            throw new InvalidArgumentException('Invalid duration unit ['.$this->getUnitsMethod().']');
        }

        return DurationImmutable::{$this->getUnitsMethod()}((int) $value);
    }

    public function set($model, string $key, $value, array $attributes): int
    {
        $method = 'total'.ucfirst($this->getUnitsMethod());

        return match (true) {
            $value instanceof DurationImmutable => $value->$method(),
            $value instanceof TimeDelta => $value->absolute()->$method(),
            is_int($value) => $value,
            default => throw new InvalidArgumentException('Invalid duration value ['.gettype($value).']'),
        };
    }
}
