<?php

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;
use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;

class TimeDeltaTest extends TestCase
{
    public function testBuildersZero(): void
    {
        $delta = TimeDelta::zero();

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(0, $delta->totalMinutes);
    }

    public function testBuildersMinutes(): void
    {
        $delta = TimeDelta::minutes(15);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(15, $delta->totalMinutes);

        $delta = TimeDelta::minutes(-15);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(-15, $delta->totalMinutes);
    }

    public function testBuildersHours(): void
    {
        $delta = TimeDelta::hours(1);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(60, $delta->totalMinutes);

        $delta = TimeDelta::hours(-1);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(-60, $delta->totalMinutes);
    }

    public function testBuildersHoursAndMinutes(): void
    {
        $delta = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(75, $delta->totalMinutes);

        $delta = TimeDelta::hoursAndMinutes(-1, -15);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(-75, $delta->totalMinutes);
    }

    public function testBuildersFromCarbon(): void
    {
        $carbon = CarbonInterval::minutes(30);

        $delta = TimeDelta::fromCarbon($carbon);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(30, $delta->totalMinutes);
    }

    public function testArithmeticAdd(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::hours(1);

        $c = $a->add($b);

        $this->assertSame(75, $c->totalMinutes);
    }

    public function testArithmeticSub(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::hours(1);

        $c = $a->sub($b);

        $this->assertSame(-45, $c->totalMinutes);
    }

    public function testArithmeticMultiply(): void
    {
        $a = TimeDelta::minutes(15);

        $b = $a->multiply(2);
        $this->assertSame(30, $b->totalMinutes);

        $c = $a->multiply(-2);
        $this->assertSame(-30, $c->totalMinutes);
    }

    public function testArithmeticCeilTo(): void
    {
        $a = TimeDelta::minutes(10);
        $b = $a->ceilTo(15);

        $this->assertSame(10, $a->totalMinutes);
        $this->assertSame(15, $b->totalMinutes);
    }

    public function testArithmeticIsOver(): void
    {
        $a = TimeDelta::hours(1);
        $b = TimeDelta::minutes(15);

        $this->assertTrue($a->isOver($b));
    }

    public function testArithmeticIsBelow(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::hours(1);

        $this->assertTrue($a->isBelow($b));
    }

    public function testIsLessThan(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::minutes(30);

        $this->assertTrue($a->isLessThan($b));
        $this->assertFalse($b->isLessThan($a));
    }

    public function testIsGreaterThan(): void
    {
        $a = TimeDelta::minutes(30);
        $b = TimeDelta::minutes(15);

        $this->assertTrue($a->isGreaterThan($b));
        $this->assertFalse($b->isGreaterThan($a));
    }

    public function testIsLessThanOrEqualTo(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::minutes(15);
        $c = TimeDelta::minutes(30);

        $this->assertTrue($a->isLessThanOrEqualTo($b));
        $this->assertTrue($b->isLessThanOrEqualTo($a));

        $this->assertTrue($a->isLessThanOrEqualTo($c));
        $this->assertFalse($c->isLessThanOrEqualTo($a));
    }

    public function testIsGreaterThanOrEqualTo(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::minutes(15);
        $c = TimeDelta::minutes(30);

        $this->assertTrue($a->isGreaterThanOrEqualTo($b));
        $this->assertTrue($b->isGreaterThanOrEqualTo($a));

        $this->assertFalse($a->isGreaterThanOrEqualTo($c));
        $this->assertTrue($c->isGreaterThanOrEqualTo($a));
    }

    public function testArithmeticEquals(): void
    {
        $this->assertTrue(TimeDelta::minutes(15)->equals(TimeDelta::minutes(15)));
        $this->assertTrue(TimeDelta::hours(1)->equals(TimeDelta::hours(1)));

        $this->assertTrue(TimeDelta::minutes(60)->equals(TimeDelta::hours(1)));
        $this->assertFalse(TimeDelta::minutes(60)->equals(TimeDelta::hours(2)));
    }

    public function testArithmeticDoesNotEqual(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::hours(1);

        $this->assertTrue($a->doesNotEqual($b));

        $a = TimeDelta::hours(1);
        $b = TimeDelta::hours(1);

        $this->assertFalse($b->doesNotEqual($a));
    }

    public function testArithmeticIsZero(): void
    {
        $this->assertTrue(TimeDelta::zero()->isZero());
        $this->assertTrue(TimeDelta::minutes(0)->isZero());
        $this->assertFalse(TimeDelta::minutes(1)->isZero());
    }

    public function testArithmeticIsNotZero(): void
    {
        $this->assertTrue(TimeDelta::minutes(1)->isNotZero());
        $this->assertTrue(TimeDelta::hours(1)->isNotZero());
        $this->assertFalse(TimeDelta::zero()->isNotZero());
    }

    public function testArithmeticMax(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::hours(1);

        $c = $a->max($b);

        $this->assertNotSame($a, $b);
        $this->assertSame($b, $c);
    }

    public function testArithmeticMin(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::hours(1);

        $c = $a->min($b);

        $this->assertNotSame($a, $b);
        $this->assertSame($a, $c);
    }

    public function testArithmeticDiff(): void
    {
        $a = TimeDelta::minutes(15);
        $b = TimeDelta::minutes(30);

        $d1 = $a->diff($b);
        $d2 = $b->diff($a);

        $this->assertSame(-15, $d1->totalMinutes);
        $this->assertSame(15, $d2->totalMinutes);
    }

    public function testFormattingFormat(): void
    {
        $a = TimeDelta::minutes(15);

        $this->assertSame('00:15', $a->format('hh:mm'));
        $this->assertSame('0:15', $a->format('h:mm'));
        $this->assertSame('15', $a->format('mm'));

        $b = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertSame('01:15', $b->format('hh:mm'));
        $this->assertSame('1:15', $b->format('h:mm'));
        $this->assertSame('1', $b->format('h'));
        $this->assertSame('15', $b->format('m'));
    }

    public function testFormattingToHuman(): void
    {
        $a = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertSame('1 hours 15 minutes', $a->toHuman());
        $this->assertSame('1--hrs 15--mins', $a->toHuman('hrs', 'mins' , '--'));
    }

    public function testFormattingToShortHuman(): void
    {
        $a = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertSame('1hrs 15m', $a->toShortHuman());
        $this->assertSame('1--h 15--m', $a->toShortHuman('h', 'm', '--'));
    }

    public function testTemporalUnitsTotalMinutes(): void
    {
        $a = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertSame(75, $a->totalMinutes);
        $this->assertSame(75, $a->totalMinutes());
    }

    public function testTemporalUnitsGetHours(): void
    {
        $a = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertSame(1, $a->getHours());
    }

    public function testTemporalUnitsGetMinutes(): void
    {
        $a = TimeDelta::hoursAndMinutes(1, 15);

        $this->assertSame(15, $a->getMinutes());
    }

    public function testIsPositive(): void
    {
        $this->assertTrue(TimeDelta::minutes(15)->isPositive());
        $this->assertFalse(TimeDelta::minutes(-15)->isPositive());
    }

    public function testIsNegative(): void
    {
        $this->assertTrue(TimeDelta::minutes(-15)->isNegative());
        $this->assertFalse(TimeDelta::minutes(15)->isNegative());
    }

    public function testInvert(): void
    {
        $this->assertSame(-15, TimeDelta::minutes(15)->invert()->totalMinutes());
        $this->assertSame(15, TimeDelta::minutes(-15)->invert()->totalMinutes());
    }

    public function testSign(): void
    {
        $this->assertSame(1, TimeDelta::minutes(10)->sign());
        $this->assertSame(-1, TimeDelta::minutes(-10)->sign());
        $this->assertSame(0, TimeDelta::minutes(0)->sign());
    }

    public function testAbsolute(): void
    {
        $this->assertInstanceOf(DurationImmutable::class, TimeDelta::minutes(15)->absolute());
        $this->assertSame(15, TimeDelta::minutes(15)->absolute()->totalMinutes());
        $this->assertSame(15, TimeDelta::minutes(-15)->absolute()->totalMinutes());
    }
}
