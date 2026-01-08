<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests;

use PHPUnit\Framework\TestCase;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\Duration;
use Carbon\CarbonInterval;

final class ArithmeticTest extends TestCase
{
    public function testImmutableArithmetic(): void
    {
        $a = DurationImmutable::hours(1);
        $b = DurationImmutable::minutes(30);

        $c = $a->add($b);

        $this->assertSame(60, $a->totalMinutes);
        $this->assertSame(90, $c->totalMinutes);
    }

    public function testMutableArithmetic(): void
    {
        $d = Duration::minutes(60);

        $d->add(DurationImmutable::minutes(15))
            ->add(DurationImmutable::minutes(15));

        $this->assertSame(90, $d->totalMinutes);
    }

    public function testIsZero(): void
    {
        $d = Duration::minutes(0);

        $this->assertTrue($d->isZero());
    }

    public function testMaxAndMin(): void
    {
        $a = DurationImmutable::minutes(30);
        $b = DurationImmutable::minutes(45);

        $this->assertSame(45, $a->max($b)->totalMinutes);
        $this->assertSame(30, $a->min($b)->totalMinutes);
    }

    public function testHoursAndMinutes(): void
    {
        $d = DurationImmutable::hoursAndMinutes(1, 75);
        $this->assertSame(135, $d->totalMinutes);
    }

    public function testZeroDuration(): void
    {
        $this->assertTrue(DurationImmutable::zero()->isZero());
    }

    public function testImmutableMultiply(): void
    {
        $a = DurationImmutable::minutes(30);
        $b = $a->multiply(2);

        $this->assertSame(30, $a->totalMinutes);;
        $this->assertSame(60, $b->totalMinutes);;
    }

    public function testMutableMultiply(): void
    {
        $a = Duration::minutes(15);
        $b = $a->multiply(2);

        $this->assertSame(30, $a->totalMinutes);
        $this->assertSame(30, $b->totalMinutes);
    }

    public function testImmutableCeilTo(): void
    {
        $a = DurationImmutable::minutes(61);
        $b = $a->ceilTo(15);

        $this->assertSame(61, $a->totalMinutes);
        $this->assertSame(75, $b->totalMinutes);
    }

    public function testMutableCeilTo(): void
    {
        $a = Duration::minutes(61);
        $b = $a->ceilTo(15);

        $this->assertSame(75, $a->totalMinutes);
        $this->assertSame(75, $b->totalMinutes);
    }

    public function testDurationNeverNegative(): void
    {
        $d = DurationImmutable::minutes(30)
            ->sub(DurationImmutable::minutes(90));

        $this->assertSame(0, $d->totalMinutes);
    }

    public function testImmutableNeverMutates(): void
    {
        $a = DurationImmutable::minutes(30);
        $b = $a->add(DurationImmutable::minutes(15));

        $this->assertSame(30, $a->totalMinutes);
        $this->assertSame(45, $b->totalMinutes);
    }
}
