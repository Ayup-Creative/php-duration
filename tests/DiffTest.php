<?php
declare(strict_types=1);

namespace AyupCreative\Duration\Tests;

use PHPUnit\Framework\TestCase;
use AyupCreative\Duration\DurationImmutable;
use AyupCreative\Duration\TimeDelta;

final class DiffTest extends TestCase
{
    public function testDiffReturnsPositiveDeltaWhenLarger(): void
    {
        $a = DurationImmutable::minutes(90);
        $b = DurationImmutable::minutes(60);

        $delta = $a->diff($b);

        $this->assertInstanceOf(TimeDelta::class, $delta);
        $this->assertSame(30, $delta->totalMinutes);
        $this->assertTrue($delta->isPositive());
        $this->assertFalse($delta->isNegative());
    }

    public function testDiffReturnsNegativeDeltaWhenSmaller(): void
    {
        $a = DurationImmutable::minutes(45);
        $b = DurationImmutable::minutes(60);

        $delta = $a->diff($b);

        $this->assertSame(-15, $delta->totalMinutes);
        $this->assertTrue($delta->isNegative());
        $this->assertFalse($delta->isPositive());
    }

    public function testDiffReturnsZeroDeltaWhenEqual(): void
    {
        $a = DurationImmutable::minutes(60);
        $b = DurationImmutable::minutes(60);

        $delta = $a->diff($b);

        $this->assertSame(0, $delta->totalMinutes);
        $this->assertFalse($delta->isPositive());
        $this->assertFalse($delta->isNegative());
    }

    public function testDeltaAbsoluteReturnsDuration(): void
    {
        $delta = TimeDelta::minutes(-75);

        $absolute = $delta->absolute();

        $this->assertInstanceOf(DurationImmutable::class, $absolute);
        $this->assertSame(75, $absolute->totalMinutes);
    }

    public function testDeltaStringFormatting(): void
    {
        $positive = TimeDelta::minutes(75);
        $negative = TimeDelta::minutes(-75);

        $this->assertSame('1:15', (string) $positive);
        $this->assertSame('-1:15', (string) $negative);
    }

    public function testDeltaInvert(): void
    {
        $d = TimeDelta::minutes(15)->invert();
        $this->assertSame(-15, $d->totalMinutes);
    }

    public function testDeltaSign(): void
    {
        $this->assertSame(1, TimeDelta::minutes(10)->sign());
        $this->assertSame(-1, TimeDelta::minutes(-10)->sign());
        $this->assertSame(0, TimeDelta::minutes(0)->sign());
    }
}
