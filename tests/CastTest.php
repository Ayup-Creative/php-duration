<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\Casts\DurationImmutableCast;
use AyupCreative\Duration\DurationImmutable;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Capsule\Manager as Capsule;

final class CastTest extends TestCase
{
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

    public function testCastReturnsDurationImmutable(): void
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

    public function testCastPersistsCorrectly(): void
    {
        $model = new CastTestModel;

        $model->duration = DurationImmutable::minutes(45);
        $model->save();

        $this->assertSame(
            45,
            $model->getRawOriginal('duration')
        );
    }

}

class CastTestModel extends Model
{
    protected $table = 'cast_test_models';

    public $timestamps = false;

    protected $casts = [
        'duration' => DurationImmutableCast::class,
    ];

    protected $fillable = ['duration'];
}
