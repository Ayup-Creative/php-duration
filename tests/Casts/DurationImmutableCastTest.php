<?php

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationImmutableCast;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as Capsule;

class DurationImmutableCastTest extends TestCase
{
    public function testGet(): void
    {
        $model = CastTestModel::create([
            'duration' => 90,
        ]);

        $this->assertInstanceOf(
            DurationImmutable::class,
            $model->duration
        );

        $this->assertSame(90, $model->duration->totalMinutes);
    }

    public function testSetDuration(): void
    {
        $model = new CastTestModel;

        $model->duration = DurationImmutable::minutes(45);
        $model->save();

        $this->assertSame(
            45,
            $model->getRawOriginal('duration')
        );
    }

    public function testSetTimeDeltaPositive(): void
    {
        $model = new CastTestModel;

        $model->duration = TimeDelta::minutes(45);
        $model->save();

        $this->assertSame(
            45,
            $model->getRawOriginal('duration')
        );
    }

    public function testSetTimeDeltaNegative(): void
    {
        $model = new CastTestModel;

        $model->duration = TimeDelta::minutes(-45);
        $model->save();

        $this->assertSame(
            45,
            $model->getRawOriginal('duration')
        );
    }

    public static function setUpBeforeClass(): void
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Capsule::schema()->create('cast_test_models', function ($table) {
            $table->increments('id');
            $table->integer('duration');
        });
    }
}

class CastTestModel extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'cast_test_models';

    public $timestamps = false;

    protected $casts = [
        'duration' => DurationImmutableCast::class,
    ];

    protected $fillable = ['duration'];
}
