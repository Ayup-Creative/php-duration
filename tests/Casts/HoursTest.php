<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Casts\Hours;
use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;

#[CoversClass(Hours::class)]
#[CoversClass(DurationCast::class)]
#[UsesClass(Duration::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class HoursTest extends Cast
{
    public function test_get(): void
    {
        $model = CastHourTestModel::create(['duration' => 3]);

        $this->assertInstanceOf(
            DurationImmutable::class,
            $model->duration
        );

        $this->assertEquals(3, $model->duration->totalHours());
        $this->assertEquals(3, $model->getRawOriginal('duration'));
    }

    public function test_set(): void
    {
        $model = new CastHourTestModel;

        $model->duration = DurationImmutable::hours(8);
        $model->save();

        $this->assertEquals(8, $model->duration->totalHours());
        $this->assertEquals(8, $model->getRawOriginal('duration'));
    }

    public function test_null(): void
    {
        $model = new CastHourTestModel;
        $model->duration = null;
        $model->save();

        $model = $model->fresh();
        $this->assertNull($model->duration);
        $this->assertNull($model->getRawOriginal('duration'));
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
