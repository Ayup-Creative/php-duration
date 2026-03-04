<?php

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\Years;
use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Years::class)]
#[CoversClass(DurationCast::class)]
#[UsesClass(Duration::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class YearsTest extends Cast
{
    public function test_get(): void
    {
        $model = CastYearTestModel::create(['duration' => 3]);

        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(3, $model->duration->totalYears());
        $this->assertEquals(3, $model->getRawOriginal('duration'));
    }

    public function test_set(): void
    {
        $model = new CastYearTestModel;

        $model->duration = DurationImmutable::years(2);
        $model->save();

        $this->assertEquals(2, $model->duration->totalYears());
        $this->assertEquals(2, $model->getRawOriginal('duration'));

        $model->duration = 5;
        $model->save();
        $this->assertEquals(5, $model->duration->totalYears());
        $this->assertEquals(5, $model->getRawOriginal('duration'));

        $model->duration = new TimeDelta(5 * DurationImmutable::SECONDS_PER_YEAR); // 5 years
        $model->save();
        $this->assertEquals(5, $model->duration->totalYears());
        $this->assertEquals(5, $model->getRawOriginal('duration'));
    }

    public function test_null(): void
    {
        $model = new CastYearTestModel;
        $model->duration = null;
        $model->save();

        $model = $model->fresh();
        $this->assertNull($model->duration);
        $this->assertNull($model->getRawOriginal('duration'));
    }
}

class CastYearTestModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => Years::class,
    ];

    protected $fillable = ['duration'];
}
