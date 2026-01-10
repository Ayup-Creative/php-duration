# Duration

[![PHP Tests](https://github.com/ayup-creative/duration/actions/workflows/phpunit.yml/badge.svg)](https://github.com/ayup-creative/duration/actions/workflows/phpunit.yml)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/ayup-creative/duration.svg?style=flat-square)](https://packagist.org/packages/ayup-creative/duration)
[![Total Downloads](https://img.shields.io/packagist/dt/ayup-creative/duration.svg?style=flat-square)](https://packagist.org/packages/ayup-creative/duration)
[![License](https://img.shields.io/packagist/l/ayup-creative/duration.svg?style=flat-square)](https://packagist.org/packages/ayup-creative/duration)

Carbon-style mutable and immutable Duration value objects with Laravel support. This package provides a simple way to handle durations of time (hours, minutes, seconds) without the complexity of dates.

## Installation

You can install the package via composer:

```bash
composer require ayup-creative/duration
```

## Usage

The package provides two main classes: `Duration` (mutable) and `DurationImmutable` (immutable).

### Creation

```php
use AyupCreative\Duration\DurationImmutable;

// From various units
$duration = DurationImmutable::seconds(100);
$duration = DurationImmutable::minutes(5);
$duration = DurationImmutable::hours(2);
$duration = DurationImmutable::days(1);
$duration = DurationImmutable::weeks(1);
$duration = DurationImmutable::months(1); // 30.44 days
$duration = DurationImmutable::years(1);  // 365.25 days

// Combined
$duration = DurationImmutable::make(days: 1, hours: 2, minutes: 3, seconds: 4);
$duration = DurationImmutable::hoursAndMinutes(1, 30);

// From Carbon
$duration = DurationImmutable::fromCarbon(\Carbon\CarbonInterval::hours(2));
```

### Accessing Units

You can access the total time in various units:

```php
$duration = DurationImmutable::hours(2);

$duration->totalSeconds(); // 7200
$duration->totalMinutes(); // 120 (int)
$duration->toMinutes();    // 120.0 (float)
$duration->totalHours();   // 2

// Or via magic properties
$duration->totalSeconds; // 7200
$duration->totalMinutes; // 120
```

You can also get the individual parts of a decomposed duration:

```php
$duration = DurationImmutable::make(hours: 1, minutes: 30, seconds: 15);

$duration->getHours();   // 1
$duration->getMinutes(); // 30
$duration->getSeconds(); // 15
```

### Arithmetic

```php
$d1 = DurationImmutable::minutes(30);
$d2 = DurationImmutable::minutes(15);

$result = $d1->add($d2);      // 45 minutes
$result = $d1->sub($d2);      // 15 minutes
$result = $d1->multiply(2);   // 60 minutes

// Ceiling operations
$duration = DurationImmutable::seconds(65);
$duration->ceilToMinutes(1); // 120 seconds
$duration->ceilTo(30);       // 90 seconds
```

### Comparisons

```php
$d1 = DurationImmutable::minutes(30);
$d2 = DurationImmutable::minutes(60);

$d1->isLessThan($d2);          // true
$d1->isGreaterThan($d2);       // false
$d1->equals($d2);              // false
$d1->isZero();                 // false
$d1->max($d2);                 // returns $d2
```

### Formatting & Humanization

```php
$duration = DurationImmutable::make(days: 1, hours: 2, minutes: 3, seconds: 4);

// Custom format
$duration->format('dd:hh:mm:ss'); // "01:02:03:04"
$duration->format('d:h:m:s');    // "1:2:3:4"

// Human readable
$duration->toHuman();      // "1 day 2 hours"
$duration->toShortHuman(); // "1d 2h 3m"

// String conversion
(string) $duration; // "02:03" (hh:mm)
```

### TimeDelta (Negative Durations)

While `Duration` and `DurationImmutable` are always positive (clamped to 0), `TimeDelta` allows for negative durations, perfect for representing differences.

```php
use AyupCreative\Duration\TimeDelta;

$delta = new TimeDelta(-3600); // -1 hour

$delta->isNegative(); // true
$delta->absolute();   // Returns DurationImmutable of 1 hour
```

### Laravel Support

The package includes Eloquent casts for easy integration with your models.

```php
use AyupCreative\Duration\Casts\Seconds;
use AyupCreative\Duration\Casts\Minutes;
use AyupCreative\Duration\Casts\Hours;
use AyupCreative\Duration\Casts\Days;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $casts = [
        'duration_in_seconds' => Seconds::class,
        'estimate_in_hours'   => Hours::class,
    ];
}

$task = Task::find(1);
$task->duration_in_seconds; // Returns DurationImmutable instance
```

## Testing

```bash
vendor/bin/phpunit
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
