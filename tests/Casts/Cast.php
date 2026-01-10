<?php

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DurationCast::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class Cast extends TestCase
{
    public function testCastWithInvalidValueTypeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid duration value [string]');

        $model = InvalidCastModel::create(['duration' => 'dummy']);
        $model->save();
    }

    public function testCastWithInvalidUnitThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid duration unit [dummy]');

        $model = new InvalidCastModel;
        $model->setRawAttributes(['duration' => 'dummy']);
        $model->save();

        $model->duration;
    }

    public function testCastWithInt(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = 100;
        $model->save();

        $this->assertEquals(100, $model->getRawOriginal('duration'));
        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(100, $model->duration->totalSeconds());
    }

    public function testCastWithTimeDelta(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = new TimeDelta(50);
        $model->save();

        $this->assertEquals(50, $model->getRawOriginal('duration'));

        $model->duration = new TimeDelta(-50);
        $model->save();
        $this->assertEquals(50, $model->getRawOriginal('duration'));
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

class InvalidCastModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => InvalidUnitCast::class,
    ];

    protected $fillable = ['duration'];
}

class TestSecondsCastModel extends Model
{
    public $timestamps = false;
    protected $table = 'cast_test_models';
    protected $casts = [
        'duration' => \AyupCreative\Duration\Casts\Seconds::class,
    ];

    protected $fillable = ['duration'];
}

class InvalidUnitCast extends DurationCast
{
    protected function getUnitsMethod(): string
    {
        return 'dummy';
    }
}
