<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Duration::class)]
#[UsesClass(DurationImmutable::class)]
class DurationTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_seconds()
    {
        $duration = new Duration(100);
        $this->assertEquals(100, $duration->totalSeconds());
    }

    /** @test */
    public function it_is_mutable_on_arithmetic_operations()
    {
        $duration = Duration::seconds(100);
        $other = Duration::seconds(50);

        $result = $duration->add($other);
        $this->assertSame($duration, $result);
        $this->assertEquals(150, $duration->totalSeconds());

        $result = $duration->sub($other);
        $this->assertSame($duration, $result);
        $this->assertEquals(100, $duration->totalSeconds());

        $result = $duration->multiply(2);
        $this->assertSame($duration, $result);
        $this->assertEquals(200, $duration->totalSeconds());
    }

    /** @test */
    public function it_cannot_go_negative_through_subtraction()
    {
        $duration = Duration::seconds(100);
        $other = Duration::seconds(150);

        $duration->sub($other);
        $this->assertEquals(0, $duration->totalSeconds());
    }

    /** @test */
    public function it_can_ceil_durations()
    {
        $duration = Duration::seconds(65); // 1m 5s

        $duration->ceilToMinutes(1);
        $this->assertEquals(120, $duration->totalSeconds());

        $duration = Duration::seconds(65);
        $duration->ceilTo(30);
        $this->assertEquals(90, $duration->totalSeconds());

        $duration = Duration::hours(1)->add(Duration::minutes(5)); // 1h 5m
        $duration->ceilToHours(1);
        $this->assertEquals(7200, $duration->totalSeconds()); // 2h

        $duration = Duration::days(1)->add(Duration::hours(5)); // 1d 5h
        $duration->ceilToDays(1);
        $this->assertEquals(172800, $duration->totalSeconds()); // 2d
    }

    /** @test */
    public function it_can_be_converted_to_immutable()
    {
        $mutable = Duration::seconds(100);
        $immutable = $mutable->toImmutable();
        $this->assertInstanceOf(DurationImmutable::class, $immutable);
        $this->assertEquals(100, $immutable->totalSeconds());
    }

    /** @test */
    public function it_supports_additional_temporal_units()
    {
        $duration = Duration::make(1, 2, 3, 4);

        $this->assertEquals(1, $duration->totalDays());
        $this->assertEquals(26, $duration->totalHours());
        $this->assertEquals(1563, $duration->totalMinutes());
        $this->assertEquals(93784, $duration->totalSeconds());

        $this->assertEquals(0, $duration->totalWeeks());
        $this->assertEquals(0, $duration->totalMonths());
        $this->assertEquals(0, $duration->totalYears());

        $this->assertEquals(2, $duration->getHours());
        $this->assertEquals(3, $duration->getMinutes());
        $this->assertEquals(4, $duration->getSeconds());
    }

    /** @test */
    public function it_supports_magic_properties()
    {
        $duration = Duration::hours(2);
        $this->assertEquals(7200, $duration->totalSeconds);
        $this->assertEquals(120, $duration->totalMinutes);
        $this->assertEquals(2, $duration->totalHours);
        $this->assertEquals(0, $duration->totalDays);
        $this->assertEquals(0, $duration->totalWeeks);
        $this->assertEquals(0, $duration->totalMonths);
        $this->assertEquals(0, $duration->totalYears);
    }
}
