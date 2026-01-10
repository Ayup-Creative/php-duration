<?php

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Casts\Minutes;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Minutes::class)]
#[CoversClass(DurationCast::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class MinutesTest extends Cast
{
    public function testGet(): void
    {
        $model = CastMinuteTestModel::create(['duration' => 3]);

        $this->assertInstanceOf(
            DurationImmutable::class,
            $model->duration
        );

        $this->assertEquals(3, $model->duration->totalMinutes());
        $this->assertEquals(3, $model->getRawOriginal('duration'));
    }

    public function testSet(): void
    {
        $model = new CastMinuteTestModel;

        $model->duration = DurationImmutable::minutes(2);
        $model->save();

        $this->assertEquals(2, $model->duration->totalMinutes());
        $this->assertEquals(2, $model->getRawOriginal('duration'));
    }
}

class CastMinuteTestModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => Minutes::class,
    ];

    protected $fillable = ['duration'];
}
