<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\Days;
use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Days::class)]
#[CoversClass(DurationCast::class)]
#[UsesClass(Duration::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class DaysTest extends Cast
{
    public function test_get(): void
    {
        $model = CastDayTestModel::create(['duration' => 3]);

        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(3, $model->duration->totalDays());
        $this->assertEquals(3, $model->getRawOriginal('duration'));
    }

    public function test_set(): void
    {
        $model = new CastDayTestModel;

        $model->duration = DurationImmutable::days(2);
        $model->save();

        $this->assertEquals(2, $model->duration->totalDays());
        $this->assertEquals(2, $model->getRawOriginal('duration'));
    }

    public function test_null(): void
    {
        $model = new CastDayTestModel;
        $model->duration = null;
        $model->save();

        $model = $model->fresh();
        $this->assertNull($model->duration);
        $this->assertNull($model->getRawOriginal('duration'));
    }
}

class CastDayTestModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => Days::class,
    ];

    protected $fillable = ['duration'];
}
