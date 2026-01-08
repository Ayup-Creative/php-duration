<?php

declare(strict_types=1);

namespace AyupCreative\Duration\Tests;

use AyupCreative\Duration\DurationImmutable;
use Carbon\CarbonInterval;
use PHPUnit\Framework\TestCase;

final class ConversionTest extends TestCase
{
    public function testConversions(): void
    {
        $immutable = DurationImmutable::minutes(120);
        $mutable = $immutable->toMutable();

        $mutable->sub(DurationImmutable::minutes(30));

        $this->assertSame(120, $immutable->totalMinutes);
        $this->assertSame(90, $mutable->totalMinutes);
    }

    public function testToString(): void
    {
        $d = DurationImmutable::minutes(75);
        $this->assertSame('1:15', (string) $d);
    }

    public function testFormatting(): void
    {
        $d = DurationImmutable::minutes(75);
        $this->assertSame('1 h 15 m', $d->format('%h h %d m'));

        $d = DurationImmutable::minutes(63);
        $this->assertSame('1 h 3 m', $d->format('%h h %d m'));

        $d = DurationImmutable::minutes(65);
        $this->assertSame('1 h 05 m', $d->format('%h h %02d m'));
    }

    public function testCarbonInterop(): void
    {
        $carbon = CarbonInterval::minutes(45);
        $d = DurationImmutable::fromCarbon($carbon);

        $this->assertSame(45, $d->totalMinutes);
    }

    public function testJsonSerialisation(): void
    {
        $d = DurationImmutable::minutes(30);
        $this->assertSame('30', json_encode($d));
    }

    public function testToHuman(): void
    {
        $this->assertSame('1 hours 15 minutes', DurationImmutable::minutes(75)->toHuman());
    }
}
