<?php

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Casts\Seconds;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Seconds::class)]
#[CoversClass(DurationCast::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
class SecondsTest extends Cast
{
    public function testGet(): void
    {
        $model = CastSecondTestModel::create(['duration' => 3]);

        $this->assertInstanceOf(
            DurationImmutable::class,
            $model->duration
        );

        $this->assertEquals(3, $model->duration->totalSeconds());
        $this->assertEquals(3, $model->getRawOriginal('duration'));
    }

    public function testSet(): void
    {
        $model = new CastSecondTestModel;

        $model->duration = DurationImmutable::seconds(20);
        $model->save();

        $this->assertEquals(20, $model->getRawOriginal('duration'));
        $this->assertEquals(20, $model->duration->totalSeconds());
    }
}

class CastSecondTestModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => Seconds::class,
    ];

    protected $fillable = ['duration'];
}
