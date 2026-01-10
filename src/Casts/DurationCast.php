<?php
declare(strict_types=1);

namespace AyupCreative\Duration\Casts;

use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

abstract class DurationCast implements CastsAttributes
{
    abstract protected function getUnitsMethod(): string;

    public function get($model, string $key, $value, array $attributes): ?DurationImmutable
    {
        if (is_null($value)) {
            return null;
        }

        if(!method_exists(DurationImmutable::class, $this->getUnitsMethod())) {
            throw new InvalidArgumentException('Invalid duration unit ['.$this->getUnitsMethod().']');
        }

        return DurationImmutable::{$this->getUnitsMethod()}((int) $value);
    }

    public function set($model, string $key, $value, array $attributes): ?int
    {
        if (is_null($value)) {
            return null;
        }

        $method = 'total'.ucfirst($this->getUnitsMethod());

        return match (true) {
            $value instanceof DurationImmutable, $value instanceof Duration => $value->$method(),
            $value instanceof TimeDelta => $value->absolute()->$method(),
            is_int($value) => $value,
            is_numeric($value) => (int) $value,
            default => throw new InvalidArgumentException('Invalid duration value ['.gettype($value).']'),
        };
    }
}
