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
    /**
     * Get the name of the method used to create the duration instance.
     *
     * @return string
     */
    abstract protected function getUnitsMethod(): string;

    /**
     * Cast the given value to a DurationImmutable instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return \AyupCreative\Duration\DurationImmutable|null
     * @throws \InvalidArgumentException
     * @see \AyupCreative\Duration\Tests\Casts\Cast::test_cast_with_int()
     * @see \AyupCreative\Duration\Tests\Casts\Cast::test_cast_with_numeric_string()
     * @see \AyupCreative\Duration\Tests\Casts\Cast::test_cast_with_null()
     */
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

    /**
     * Prepare the given value for storage.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $key
     * @param mixed $value
     * @param array $attributes
     * @return int|null
     * @throws \InvalidArgumentException
     * @see \AyupCreative\Duration\Tests\Casts\Cast::test_cast_with_duration()
     * @see \AyupCreative\Duration\Tests\Casts\Cast::test_cast_with_time_delta()
     */
    public function set($model, string $key, $value, array $attributes): ?int
    {
        if (is_null($value)) {
            return null;
        }

        $method = 'total'.ucfirst($this->getUnitsMethod());

        $result = match (true) {
            $value instanceof DurationImmutable, $value instanceof Duration => $value->$method(),
            $value instanceof TimeDelta => $value->$method(),
            is_int($value) => $value,
            is_numeric($value) => (int) $value,
            default => throw new InvalidArgumentException('Invalid duration value ['.gettype($value).']'),
        };

        return max(0, $result);
    }
}
