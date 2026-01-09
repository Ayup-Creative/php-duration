<?php

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;

class DurationImmutableTest extends TestCase
{
    public function testBuildFromZeroReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(DurationImmutable::class, DurationImmutable::zero());
    }

    public function testBuildFromMinutesReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(DurationImmutable::class, DurationImmutable::minutes(15));
    }

    public function testBuildFromHoursReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(DurationImmutable::class, DurationImmutable::hours(1));
    }

    public function testBuildFromHoursAndMinutesReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(DurationImmutable::class, DurationImmutable::hoursAndMinutes(1, 15));
    }

    public function testBuildFromFromCarbonReturnsMutableDuration(): void
    {
        $carbon = CarbonInterval::minutes(30);

        $this->assertInstanceOf(DurationImmutable::class, DurationImmutable::fromCarbon($carbon));
    }

    public function testImmutability(): void
    {
        $a = DurationImmutable::minutes(30);
        $b = $a->add(DurationImmutable::minutes(15));

        $this->assertSame(30, $a->totalMinutes);
        $this->assertSame(45, $b->totalMinutes);
    }

    public function testArithmeticAdd(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $b = $a->add($b);

        $this->assertSame(15, $a->totalMinutes);
        $this->assertSame(75, $b->totalMinutes);
    }

    public function testArithmeticSub(): void
    {
        $a = DurationImmutable::hours(1);
        $b = DurationImmutable::minutes(15);

        $b = $a->sub($b);

        $this->assertSame(60, $a->totalMinutes);
        $this->assertSame(45, $b->totalMinutes);
    }

    public function testArithmeticSubNeverGoesNegative(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $b = $a->sub($b);

        $this->assertSame(15, $a->totalMinutes);
        $this->assertSame(0, $b->totalMinutes);
    }

    public function testArithmeticMultiply(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = $a->multiply(2);

        $this->assertSame(15, $a->totalMinutes);
        $this->assertSame(30, $b->totalMinutes);
    }

    public function testArithmeticCeilTo(): void
    {
        $a = DurationImmutable::minutes(10);
        $b = $a->ceilTo(15);

        $c = DurationImmutable::hours(2);
        $d = $c->ceilTo(300);

        $this->assertSame(10, $a->totalMinutes);
        $this->assertSame(15, $b->totalMinutes);

        $this->assertSame(120, $c->totalMinutes);
        $this->assertSame(300, $d->totalMinutes);
    }

    public function testArithmeticIsOver(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $this->assertFalse($a->isOver($b));
        $this->assertTrue($b->isOver($a));
    }

    public function testArithmeticIsBelow(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $this->assertTrue($a->isBelow($b));
        $this->assertFalse($b->isBelow($a));
    }

    public function testIsLessThan(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::minutes(30);

        $this->assertTrue($a->isLessThan($b));
        $this->assertFalse($b->isLessThan($a));
    }

    public function testIsGreaterThan(): void
    {
        $a = DurationImmutable::minutes(30);
        $b = DurationImmutable::minutes(15);

        $this->assertTrue($a->isGreaterThan($b));
        $this->assertFalse($b->isGreaterThan($a));
    }

    public function testIsLessThanOrEqualTo(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::minutes(15);
        $c = DurationImmutable::minutes(30);

        $this->assertTrue($a->isLessThanOrEqualTo($b));
        $this->assertTrue($b->isLessThanOrEqualTo($a));

        $this->assertTrue($a->isLessThanOrEqualTo($c));
        $this->assertFalse($c->isLessThanOrEqualTo($a));
    }

    public function testIsGreaterThanOrEqualTo(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::minutes(15);
        $c = DurationImmutable::minutes(30);

        $this->assertTrue($a->isGreaterThanOrEqualTo($b));
        $this->assertTrue($b->isGreaterThanOrEqualTo($a));

        $this->assertFalse($a->isGreaterThanOrEqualTo($c));
        $this->assertTrue($c->isGreaterThanOrEqualTo($a));
    }

    public function testArithmeticEquals(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $this->assertFalse($b->equals($a));

        $a = DurationImmutable::hours(1);
        $b = DurationImmutable::hours(1);

        $this->assertTrue($a->equals($b));
    }

    public function testArithmeticDoesNotEqual(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $this->assertTrue($a->doesNotEqual($b));

        $a = DurationImmutable::hours(1);
        $b = DurationImmutable::hours(1);

        $this->assertFalse($b->doesNotEqual($a));
    }

    public function testArithmeticIsZero(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(0);
        $c = DurationImmutable::zero();

        $this->assertFalse($a->isZero());
        $this->assertTrue($b->isZero());
        $this->assertTrue($c->isZero());
    }

    public function testArithmeticIsNotZero(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);
        $c = DurationImmutable::zero();

        $this->assertTrue($a->isNotZero());
        $this->assertTrue($b->isNotZero());
        $this->assertFalse($c->isNotZero());
    }

    public function testArithmeticMax(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $c = $a->max($b);

        $this->assertSame($b, $c);
        $this->assertNotSame($a, $c);
        $this->assertNotSame($a, $b);
    }

    public function testArithmeticMin(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $c = $a->min($b);

        $this->assertSame($a, $c);
        $this->assertNotSame($b, $c);
        $this->assertNotSame($a, $b);
    }

    public function testArithmeticDiffReturnsTimeDelta(): void
    {
        $a = DurationImmutable::minutes(15);
        $b = DurationImmutable::hours(1);

        $d1 = $a->diff($b);
        $this->assertInstanceOf(\AyupCreative\Duration\TimeDelta::class, $d1);
    }

    public function testArithmeticDiffReturnsNegativeDeltaWhenSmaller(): void
    {
        $a = DurationImmutable::minutes(45);
        $b = DurationImmutable::hours(1);

        $d1 = $a->diff($b);
        $this->assertSame(-15, $d1->totalMinutes);
    }

    public function testArithmeticDiffReturnsPositiveDeltaWhenLarger(): void
    {
        $a = DurationImmutable::hours(1);
        $b = DurationImmutable::minutes(45);

        $d1 = $a->diff($b);
        $this->assertSame(15, $d1->totalMinutes);
    }

    public function testArithmeticDiffReturnsZeroDeltaWhenEqual(): void
    {
        $a = DurationImmutable::hours(1);
        $b = DurationImmutable::hours(1);

        $d1 = $a->diff($b);
        $this->assertSame(0, $d1->totalMinutes);
    }

    public function testFormattingFormat(): void
    {
        $a = DurationImmutable::minutes(15);

        $this->assertSame('00:15', $a->format('hh:mm'));
        $this->assertSame('0:15', $a->format('h:mm'));
        $this->assertSame('15', $a->format('mm'));

        $b = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertSame('01:15', $b->format('hh:mm'));
        $this->assertSame('1:15', $b->format('h:mm'));
        $this->assertSame('1', $b->format('h'));
        $this->assertSame('15', $b->format('m'));
    }

    public function testFormattingToHuman(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertSame('1 hours 15 minutes', $a->toHuman());
        $this->assertSame('1--hrs 15--mins', $a->toHuman('hrs', 'mins' , '--'));
    }

    public function testFormattingToShortHuman(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertSame('1hrs 15m', $a->toShortHuman());
        $this->assertSame('1--h 15--m', $a->toHuman('h', 'm', '--'));
    }

    public function testTemporalUnitsTotalMinutes(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertSame(75, $a->totalMinutes);
        $this->assertSame(75, $a->totalMinutes());
    }

    public function testTemporalUnitsGetHours(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertSame(1, $a->getHours());
    }

    public function testTemporalUnitsGetMinutes(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertSame(15, $a->getMinutes());
    }

    public function testToDateInterval(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $d = $a->toDateInterval();

        $this->assertInstanceOf(\DateInterval::class, $d);
        $this->assertSame('00:75', $d->format('%H:%I'));
    }

    public function testToMutable(): void
    {
        $a = DurationImmutable::hoursAndMinutes(1, 15);

        $this->assertInstanceOf(Duration::class, $a->toMutable());
    }
}
