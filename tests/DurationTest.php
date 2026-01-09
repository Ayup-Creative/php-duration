<?php

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\Duration;
use AyupCreative\Duration\DurationImmutable;
use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;

class DurationTest extends TestCase
{
    public function testBuildFromZeroReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(Duration::class, Duration::zero());
    }

    public function testBuildFromMinutesReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(Duration::class, Duration::minutes(15));
    }

    public function testBuildFromHoursReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(Duration::class, Duration::hours(1));
    }

    public function testBuildFromHoursAndMinutesReturnsMutableDuration(): void
    {
        $this->assertInstanceOf(Duration::class, Duration::hoursAndMinutes(1, 15));
    }

    public function testBuildFromFromCarbonReturnsMutableDuration(): void
    {
        $carbon = CarbonInterval::minutes(30);

        $this->assertInstanceOf(Duration::class, Duration::fromCarbon($carbon));
    }

    public function testMutability(): void
    {
        $d = Duration::minutes(30);
        $d->add(Duration::minutes(15));

        $this->assertSame(45, $d->totalMinutes);
    }

    public function testArithmeticAdd(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $a->add($b);

        $this->assertSame(75, $a->totalMinutes);
        $this->assertSame(60, $b->totalMinutes);
    }

    public function testArithmeticSub(): void
    {
        $a = Duration::hours(1);
        $b = Duration::minutes(15);

        $a->sub($b);

        $this->assertSame(45, $a->totalMinutes);
        $this->assertSame(15, $b->totalMinutes);
    }

    public function testArithmeticSubNeverGoesNegative(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $a->sub($b);

        $this->assertSame(0, $a->totalMinutes);
        $this->assertSame(60, $b->totalMinutes);
    }

    public function testArithmeticMultiply(): void
    {
        $a = Duration::minutes(15);
        $a->multiply(2);

        $this->assertSame(30, $a->totalMinutes);
    }

    public function testArithmeticCeilTo(): void
    {
        $a = Duration::minutes(10);
        $a->ceilTo(15);

        $b = Duration::hours(2);
        $b->ceilTo(300);

        $this->assertSame(15, $a->totalMinutes);
        $this->assertSame(300, $b->totalMinutes);
    }

    public function testArithmeticIsOver(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $this->assertFalse($a->isOver($b));
        $this->assertTrue($b->isOver($a));
    }

    public function testArithmeticIsBelow(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $this->assertTrue($a->isBelow($b));
        $this->assertFalse($b->isBelow($a));
    }

    public function testArithmeticEquals(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $this->assertFalse($b->equals($a));

        $a = Duration::hours(1);
        $b = Duration::hours(1);

        $this->assertTrue($a->equals($b));
    }

    public function testArithmeticIsZero(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(0);
        $c = Duration::zero();

        $this->assertFalse($a->isZero());
        $this->assertTrue($b->isZero());
        $this->assertTrue($c->isZero());
    }

    public function testArithmeticMax(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $c = $a->max($b);

        $this->assertSame($b, $c);
        $this->assertNotSame($a, $c);
        $this->assertNotSame($a, $b);
    }

    public function testArithmeticMin(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $c = $a->min($b);

        $this->assertSame($a, $c);
        $this->assertNotSame($b, $c);
        $this->assertNotSame($a, $b);
    }

    public function testArithmeticDiffReturnsTimeDelta(): void
    {
        $a = Duration::minutes(15);
        $b = Duration::hours(1);

        $d1 = $a->diff($b);
        $this->assertInstanceOf(\AyupCreative\Duration\TimeDelta::class, $d1);
    }

    public function testArithmeticDiffReturnsNegativeDeltaWhenSmaller(): void
    {
        $a = Duration::minutes(45);
        $b = Duration::hours(1);

        $d1 = $a->diff($b);
        $this->assertSame(-15, $d1->totalMinutes);
    }

    public function testArithmeticDiffReturnsPositiveDeltaWhenLarger(): void
    {
        $a = Duration::hours(1);
        $b = Duration::minutes(45);

        $d1 = $a->diff($b);
        $this->assertSame(15, $d1->totalMinutes);
    }

    public function testArithmeticDiffReturnsZeroDeltaWhenEqual(): void
    {
        $a = Duration::hours(1);
        $b = Duration::hours(1);

        $d1 = $a->diff($b);
        $this->assertSame(0, $d1->totalMinutes);
    }

    public function testFormattingFormat(): void
    {
        $a = Duration::minutes(15);

        $this->assertSame('00:15', $a->format('hh:mm'));
        $this->assertSame('0:15', $a->format('h:mm'));
        $this->assertSame('15', $a->format('mm'));

        $b = Duration::hoursAndMinutes(1, 15);

        $this->assertSame('01:15', $b->format('hh:mm'));
        $this->assertSame('1:15', $b->format('h:mm'));
        $this->assertSame('1', $b->format('h'));
        $this->assertSame('15', $b->format('m'));
    }

    public function testFormattingToHuman(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $this->assertSame('1 hours 15 minutes', $a->toHuman());
        $this->assertSame('1--hrs 15--mins', $a->toHuman('hrs', 'mins' , '--'));
    }

    public function testFormattingToShortHuman(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $this->assertSame('1hrs 15m', $a->toShortHuman());
        $this->assertSame('1--h 15--m', $a->toHuman('h', 'm', '--'));
    }

    public function testTemporalUnitsTotalMinutes(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $this->assertSame(75, $a->totalMinutes);
        $this->assertSame(75, $a->totalMinutes());
    }

    public function testTemporalUnitsGetHours(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $this->assertSame(1, $a->getHours());
    }

    public function testTemporalUnitsGetMinutes(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $this->assertSame(15, $a->getMinutes());
    }

    public function testToDateInterval(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $d = $a->toDateInterval();

        $this->assertInstanceOf(\DateInterval::class, $d);
        $this->assertSame('00:75', $d->format('%H:%I'));
    }

    public function testToImmutable(): void
    {
        $a = Duration::hoursAndMinutes(1, 15);

        $this->assertInstanceOf(DurationImmutable::class, $a->toImmutable());
    }
}
