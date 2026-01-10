<?php

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Carbon\CarbonInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DurationImmutable::class)]
#[UsesClass(Duration::class)]
#[UsesClass(TimeDelta::class)]
class DurationImmutableTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_seconds()
    {
        $duration = new DurationImmutable(100);
        $this->assertEquals(100, $duration->totalSeconds());
    }

    /** @test */
    public function it_prevents_negative_seconds_in_constructor()
    {
        $duration = new DurationImmutable(-100);
        $this->assertEquals(0, $duration->totalSeconds());
    }

    /** @test */
    public function it_is_immutable_on_arithmetic_operations()
    {
        $d1 = DurationImmutable::seconds(100);
        $d2 = DurationImmutable::seconds(50);
        $mutable = Duration::seconds(25);

        $added = $d1->add($d2);
        $this->assertNotSame($d1, $added);
        $this->assertEquals(100, $d1->totalSeconds());
        $this->assertEquals(150, $added->totalSeconds());

        $addedMutable = $d1->add($mutable);
        $this->assertEquals(125, $addedMutable->totalSeconds());

        $subbed = $d1->sub($d2);
        $this->assertNotSame($d1, $subbed);
        $this->assertEquals(100, $d1->totalSeconds());
        $this->assertEquals(50, $subbed->totalSeconds());

        $multiplied = $d1->multiply(2);
        $this->assertNotSame($d1, $multiplied);
        $this->assertEquals(100, $d1->totalSeconds());
        $this->assertEquals(200, $multiplied->totalSeconds());
    }

    /** @test */
    public function it_cannot_go_negative_through_subtraction()
    {
        $d1 = DurationImmutable::seconds(100);
        $d2 = DurationImmutable::seconds(150);

        $result = $d1->sub($d2);
        $this->assertEquals(0, $result->totalSeconds());
    }

    /** @test */
    public function it_can_create_from_various_units()
    {
        $this->assertEquals(0, DurationImmutable::zero()->totalSeconds());
        $this->assertEquals(1, DurationImmutable::seconds(1)->totalSeconds());
        $this->assertEquals(60, DurationImmutable::minutes(1)->totalSeconds());
        $this->assertEquals(3600, DurationImmutable::hours(1)->totalSeconds());
        $this->assertEquals(86400, DurationImmutable::days(1)->totalSeconds());
        $this->assertEquals(604800, DurationImmutable::weeks(1)->totalSeconds());
        $this->assertEquals(2629800, DurationImmutable::months(1)->totalSeconds());
        $this->assertEquals(31557600, DurationImmutable::years(1)->totalSeconds());

        $this->assertEquals(3660, DurationImmutable::hoursAndMinutes(1, 1)->totalSeconds());
        $this->assertEquals(90061, DurationImmutable::make(1, 1, 1, 1)->totalSeconds());
    }

    /** @test */
    public function it_can_convert_to_various_units()
    {
        $duration = DurationImmutable::hours(1);
        $this->assertEquals(3600, $duration->toSeconds());
        $this->assertEquals(60, $duration->toMinutes());
        $this->assertEquals(1, $duration->toHours());
        $this->assertEquals(1/24, $duration->toDays());
        $this->assertEquals(1/168, $duration->toWeeks(), '', 0.00001);

        // This test might fail due to bug in Conversion trait
        $this->assertEquals(3600 / 2629800, $duration->toMonths(), '', 0.00001);
        $this->assertEquals(3600 / 31557600, $duration->toYears(), '', 0.00001);
    }

    /** @test */
    public function it_can_convert_to_external_types()
    {
        $duration = DurationImmutable::hours(1);

        $carbon = $duration->toCarbonInterval();
        $this->assertInstanceOf(CarbonInterval::class, $carbon);
        $this->assertEquals(3600, $carbon->totalSeconds);

        $dateInterval = $duration->toDateInterval();
        $this->assertInstanceOf(\DateInterval::class, $dateInterval);
        $this->assertEquals(1, $dateInterval->h);
    }

    /** @test */
    public function it_supports_comparison_methods()
    {
        $d100 = DurationImmutable::seconds(100);
        $d200 = DurationImmutable::seconds(200);
        $d100_2 = DurationImmutable::seconds(100);

        $this->assertTrue($d100->isBelow($d200));
        $this->assertTrue($d100->isLessThan($d200));
        $this->assertTrue($d200->isOver($d100));
        $this->assertTrue($d200->isGreaterThan($d100));

        $this->assertTrue($d100->equals($d100_2));
        $this->assertFalse($d100->equals($d200));
        $this->assertTrue($d100->doesNotEqual($d200));

        $this->assertTrue($d100->isLessThanOrEqualTo($d100_2));
        $this->assertTrue($d100->isLessThanOrEqualTo($d200));
        $this->assertTrue($d200->isGreaterThanOrEqualTo($d100));
        $this->assertTrue($d100->isGreaterThanOrEqualTo($d100_2));

        $this->assertTrue(DurationImmutable::zero()->isZero());
        $this->assertFalse($d100->isZero());
        $this->assertTrue($d100->isNotZero());
        $this->assertFalse(DurationImmutable::zero()->isNotZero());
    }

    /** @test */
    public function it_can_calculate_min_and_max()
    {
        $d1 = DurationImmutable::seconds(100);
        $d2 = DurationImmutable::seconds(200);
        $mutable = Duration::seconds(300);

        $this->assertSame($d2, $d1->max($d2));
        $this->assertSame($d1, $d1->max($d1)); // Cover return $this
        $this->assertEquals(300, $d1->max($mutable)->totalSeconds()); // Cover new self
        $this->assertInstanceOf(DurationImmutable::class, $d1->max($mutable));

        $this->assertSame($d1, $d1->min($d2));
        $this->assertSame($d2, $d2->min($d2)); // Cover return $this
        $this->assertEquals(100, $d2->min($d1)->totalSeconds());
    }

    /** @test */
    public function it_supports_formatting_zeros()
    {
        $zero = DurationImmutable::zero();
        $this->assertEquals('0 seconds', $zero->toHuman());
        $this->assertEquals('0s', $zero->toShortHuman());
    }

    /** @test */
    public function it_can_calculate_diff_as_timedelta()
    {
        $d1 = DurationImmutable::seconds(100);
        $d2 = DurationImmutable::seconds(150);

        $diff = $d1->diff($d2);
        $this->assertInstanceOf(TimeDelta::class, $diff);
        $this->assertEquals(-50, $diff->totalSeconds());
    }

    /** @test */
    public function it_supports_formatting()
    {
        $duration = DurationImmutable::make(1, 2, 3, 4); // 1d 2h 3m 4s
        $this->assertEquals('01:02:03:04', $duration->format('dd:hh:mm:ss'));
        $this->assertEquals('1:2:3:4', $duration->format('d:h:m:s'));
        $this->assertEquals('02:03', (string)$duration); // *hh:mm
    }

    /** @test */
    public function it_supports_human_formatting()
    {
        $this->assertEquals('1 day 2 hours', DurationImmutable::make(1, 2, 3, 4)->toHuman());
        $this->assertEquals('2 hours 3 minutes', DurationImmutable::make(0, 2, 3, 4)->toHuman());
        $this->assertEquals('4 seconds', DurationImmutable::seconds(4)->toHuman());
        $this->assertEquals('1 day 1 minute', DurationImmutable::make(1, 0, 1, 0)->toHuman());

        $this->assertEquals('CUSTOM', DurationImmutable::seconds(10)->toHuman(fn() => 'CUSTOM'));
    }

    /** @test */
    public function it_supports_short_human_formatting()
    {
        $this->assertEquals('1d 2h 3m 4s', DurationImmutable::make(1, 2, 3, 4)->toShortHuman());
        $this->assertEquals('4s', DurationImmutable::seconds(4)->toShortHuman());
    }

    /** @test */
    public function it_can_ceil_durations()
    {
        $duration = DurationImmutable::seconds(65); // 1m 5s

        $this->assertEquals(120, $duration->ceilToMinutes(1)->totalSeconds());
        $this->assertEquals(90, $duration->ceilTo(30)->totalSeconds());
        $this->assertSame($duration, $duration->ceilTo(0));

        $duration = DurationImmutable::hours(1)->add(DurationImmutable::minutes(5)); // 1h 5m
        $this->assertEquals(7200, $duration->ceilToHours(1)->totalSeconds()); // 2h

        $duration = DurationImmutable::days(1)->add(DurationImmutable::hours(5)); // 1d 5h
        $this->assertEquals(172800, $duration->ceilToDays(1)->totalSeconds()); // 2d
    }

    /** @test */
    public function it_can_create_from_carbon()
    {
        $carbon = \Carbon\CarbonInterval::hours(2);
        $duration = DurationImmutable::fromCarbon($carbon);

        $this->assertInstanceOf(DurationImmutable::class, $duration);
        $this->assertEquals(7200, $duration->totalSeconds());
    }

    /** @test */
    public function it_supports_additional_temporal_units()
    {
        $duration = DurationImmutable::make(1, 2, 3, 4); // 1d 2h 3m 4s

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

        $large = DurationImmutable::weeks(2);
        $this->assertEquals(2, $large->totalWeeks());
        
        $month = DurationImmutable::months(1);
        $this->assertEquals(1, $month->totalMonths());

        $year = DurationImmutable::years(1);
        $this->assertEquals(1, $year->totalYears());
    }

    /** @test */
    public function it_supports_magic_properties()
    {
        $duration = DurationImmutable::hours(2);
        $this->assertEquals(7200, $duration->totalSeconds);
        $this->assertEquals(120, $duration->totalMinutes);
        $this->assertEquals(2, $duration->totalHours);
        $this->assertEquals(0, $duration->totalDays);
        $this->assertEquals(0, $duration->totalWeeks);
        $this->assertEquals(0, $duration->totalMonths);
        $this->assertEquals(0, $duration->totalYears);

        $this->assertEquals(1, DurationImmutable::months(1)->totalMonths);
        $this->assertEquals(1, DurationImmutable::years(1)->totalYears);
    }

    /** @test */
    public function it_throws_error_on_undefined_magic_property()
    {
        $this->expectException(\Error::class);
        $duration = DurationImmutable::seconds(1);
        $duration->nonExistent;
    }

    /** @test */
    public function it_throws_error_on_setting_magic_property()
    {
        $this->expectException(\Error::class);
        $duration = DurationImmutable::seconds(1);
        $duration->totalSeconds = 10;
    }

    /** @test */
    public function it_can_be_converted_to_mutable()
    {
        $immutable = DurationImmutable::seconds(100);
        $mutable = $immutable->toMutable();
        $this->assertInstanceOf(Duration::class, $mutable);
        $this->assertEquals(100, $mutable->totalSeconds());
    }

    /** @test */
    public function it_is_json_serializable()
    {
        $duration = DurationImmutable::seconds(100);
        $this->assertEquals(100, json_decode(json_encode($duration)));
    }
}
