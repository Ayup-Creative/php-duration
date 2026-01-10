<?php

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Casts\Hours;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Hours::class)]
#[CoversClass(DurationCast::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class HoursTest extends Cast
{
    public function testGet(): void
    {
        $model = CastHourTestModel::create(['duration' => 3]);

        $this->assertInstanceOf(
            DurationImmutable::class,
            $model->duration
        );

        $this->assertEquals(3, $model->duration->totalHours());
        $this->assertEquals(3, $model->getRawOriginal('duration'));
    }

    public function testSet(): void
    {
        $model = new CastHourTestModel;

        $model->duration = DurationImmutable::hours(8);
        $model->save();

        $this->assertEquals(8, $model->duration->totalHours());
        $this->assertEquals(8, $model->getRawOriginal('duration'));
    }
}

class CastHourTestModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => Hours::class,
    ];

    protected $fillable = ['duration'];
}
