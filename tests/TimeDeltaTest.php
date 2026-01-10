<?php

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(TimeDelta::class)]
#[UsesClass(DurationImmutable::class)]
class TimeDeltaTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated_with_positive_seconds()
    {
        $delta = new TimeDelta(100);
        $this->assertEquals(100, $delta->totalSeconds());
        $this->assertTrue($delta->isPositive());
        $this->assertFalse($delta->isNegative());
        $this->assertEquals(1, $delta->sign());
    }

    /** @test */
    public function it_can_be_instantiated_with_negative_seconds()
    {
        $delta = new TimeDelta(-100);
        $this->assertEquals(-100, $delta->totalSeconds());
        $this->assertFalse($delta->isPositive());
        $this->assertTrue($delta->isNegative());
        $this->assertEquals(-1, $delta->sign());
    }

    /** @test */
    public function it_is_immutable_on_arithmetic_operations()
    {
        $delta = TimeDelta::seconds(100);
        $other = TimeDelta::seconds(50);

        $added = $delta->add($other);
        $this->assertNotSame($delta, $added);
        $this->assertEquals(100, $delta->totalSeconds());
        $this->assertEquals(150, $added->totalSeconds());

        $subbed = $delta->sub($other);
        $this->assertNotSame($delta, $subbed);
        $this->assertEquals(100, $delta->totalSeconds());
        $this->assertEquals(50, $subbed->totalSeconds());
    }

    /** @test */
    public function it_can_go_negative()
    {
        $d1 = TimeDelta::seconds(100);
        $d2 = TimeDelta::seconds(150);

        $result = $d1->sub($d2);
        $this->assertEquals(-50, $result->totalSeconds());
    }

    /** @test */
    public function it_can_invert_its_value()
    {
        $delta = new TimeDelta(100);
        $inverted = $delta->invert();
        $this->assertEquals(-100, $inverted->totalSeconds());

        $this->assertEquals(100, $inverted->invert()->totalSeconds());
    }

    /** @test */
    public function it_can_return_absolute_duration()
    {
        $delta = new TimeDelta(-100);
        $absolute = $delta->absolute();
        $this->assertInstanceOf(DurationImmutable::class, $absolute);
        $this->assertEquals(100, $absolute->totalSeconds());
    }

    /** @test */
    public function it_supports_comparison_methods()
    {
        $delta = new TimeDelta(-100);
        $zero = TimeDelta::zero();

        $this->assertTrue($delta->isNegative());
        $this->assertFalse($delta->isPositive());
        $this->assertTrue($delta->isNotZero());

        $this->assertTrue($zero->isZero());
        $this->assertFalse($zero->isNotZero());
        $this->assertFalse($zero->isPositive());
        $this->assertFalse($zero->isNegative());
    }

    /** @test */
    public function it_supports_formatting_with_sign()
    {
        $delta = new TimeDelta(-3661); // -1h 1m 1s
        $this->assertEquals('-01:01:01', $delta->format('*hh:mm:ss'));
        $this->assertEquals('-1h 1m', $delta->toShortHuman());
        $this->assertEquals('-1 hour 1 minute', $delta->toHuman());
    }

    /** @test */
    public function it_can_convert_to_date_interval()
    {
        $delta = new TimeDelta(-3661);
        $interval = $delta->toDateInterval();

        $this->assertInstanceOf(\DateInterval::class, $interval);
        $this->assertEquals(1, $interval->h);
        $this->assertEquals(1, $interval->i);
        $this->assertEquals(1, $interval->s);
        $this->assertEquals(1, $interval->invert);
    }
}
