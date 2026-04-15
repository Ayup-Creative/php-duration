<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests\Casts;

use AyupCreative\Duration\Casts\DurationCast;
use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DurationCast::class)]
#[UsesClass(Duration::class)]
#[UsesClass(DurationImmutable::class)]
#[UsesClass(TimeDelta::class)]
#[UsesClass(\AyupCreative\Duration\Casts\Seconds::class)]
class Cast extends TestCase
{
    public function test_cast_with_invalid_value_type_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid duration value [string]');

        $model = InvalidCastModel::create(['duration' => 'dummy']);
        $model->save();
    }

    public function test_cast_with_invalid_unit_throws_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid duration unit [dummy]');

        $model = new InvalidCastModel;
        $model->setRawAttributes(['duration' => 'dummy']);
        $model->save();

        $model->duration;
    }

    public function test_cast_with_int(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = 100;
        $model->save();

        $model = $model->fresh();
        $this->assertEquals(100, $model->getRawOriginal('duration'));
        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(100, $model->duration->totalSeconds());
    }

    public function test_cast_with_time_delta(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = new TimeDelta(50);
        $model->save();

        $model = $model->fresh();
        $this->assertEquals(50, $model->getRawOriginal('duration'));
        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(50, $model->duration->totalSeconds());

        $model->duration = new TimeDelta(-50);
        $model->save();
        $model = $model->fresh();
        $this->assertEquals(0, $model->getRawOriginal('duration'));
    }

    public function test_cast_with_duration(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = Duration::seconds(100);
        $model->save();

        $model = $model->fresh();
        $this->assertEquals(100, $model->getRawOriginal('duration'));
        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(100, $model->duration->totalSeconds());
    }

    public function test_cast_with_null(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = null;
        $model->save();

        $model = $model->fresh();
        $this->assertNull($model->getRawOriginal('duration'));
        $this->assertNull($model->duration);
    }

    public function test_cast_with_numeric_string(): void
    {
        $model = new TestSecondsCastModel;
        $model->duration = '123';
        $model->save();

        $model = $model->fresh();
        $this->assertEquals(123, $model->getRawOriginal('duration'));
        $this->assertInstanceOf(DurationImmutable::class, $model->duration);
        $this->assertEquals(123, $model->duration->totalSeconds());
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
            $table->integer('duration')->nullable();
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
