# CarbonInterval

The CarbonInterval class is [inherited](https://www.php.net/manual/en/language.oop5.inheritance.php) from the PHP [DateInterval](https://www.php.net/manual/en/class.dateinterval.php) class.

```php
<?php
class CarbonInterval extends \DateInterval
{
	// code here
}

```

You can create an instance in the following ways:

```php
{{::exec(
echo CarbonInterval::createFromFormat('H:i:s', '10:20:00');/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(
echo CarbonInterval::year();/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(echo CarbonInterval::months(3);/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(echo CarbonInterval::days(3)->addSeconds(32);/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(echo CarbonInterval::weeks(3);/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(echo CarbonInterval::days(23);/*pad(54)*/)}} // {{eval}}
echo "\n";
// years, months, weeks, days, hours, minutes, seconds, microseconds
{{::exec(echo CarbonInterval::create(2, 0, 5, 1, 1, 2, 7, 123);/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(echo CarbonInterval::createFromDateString('3 months');/*pad(54)*/)}} // {{eval}}

```

Be careful, Carbon 2 accepts only integer unit values by default, which means: `CarbonInterval::days(3.5)` produces a \[3 days and 0 hours\] interval. In Carbon 3, it will cascade decimal part to smaller units. To enable this behavior in Carbon 2, you can call `CarbonInterval::enableFloatSetters();`.

```php
{{::exec(
// Allow decimal numbers
CarbonInterval::enableFloatSetters();
echo CarbonInterval::days(3.5);/*pad(54)*/)}} // {{eval}}
echo "\n";
{{::exec(
// Disallow decimal numbers
CarbonInterval::enableFloatSetters(false);
echo CarbonInterval::days(3.5);/*pad(54)*/)}} // {{eval}}

```

You can add/sub any unit to a given existing interval:

```php
{{::exec(
$interval = CarbonInterval::months(3);
echo $interval;/*pad(54)*/)}} // {{eval}}
echo "\n";

{{::exec(
$interval->subMonths(1);
echo $interval;/*pad(54)*/)}} // {{eval}}
echo "\n";

{{::exec(
$interval->addDays(15);
echo $interval;/*pad(54)*/)}} // {{eval}}

```

We also provide `plus()` and `minus()` method that expect numbers for each unit in the same order than `create()` and can be used in a convenient way with PHP 8:

```php
{{::exec(
$interval = CarbonInterval::months(3);
echo $interval;/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::exec(
$interval->minus(months: 1);
echo $interval;/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::exec(
$interval->plus(days: 15, hours: 20);
echo $interval;/*pad(40)*/)}} // {{eval}}

```

If you find yourself inheriting a `\DateInterval` instance from another library, fear not! You can create a `CarbonInterval` instance via a friendly `instance()` function.

```php
{{::lint($di = new \DateInterval('P1Y2M');)}} // <== instance from another API
{{::lint($ci = CarbonInterval::instance($di);)}}
{{::exec(echo get_class($ci);/*pad(54)*/)}} // '{{eval}}'
{{::exec(echo $ci;/*pad(54)*/)}} // {{eval}}

// It creates a new copy of the object when given a CarbonInterval value
{{::lint($ci2 = CarbonInterval::instance($ci);)}}
{{::exec(var_dump($ci2 === $ci);/*pad(54)*/)}} // {{eval}}

// but you can tell to return same object if already an instance of  CarbonInterval
{{::lint($ci3 = CarbonInterval::instance($ci, skipCopy: true);)}}
{{::exec(var_dump($ci3 === $ci);/*pad(54)*/)}} // {{eval}}

// the same option is available on make()
{{::lint($ci4 = CarbonInterval::make($ci, skipCopy: true);)}}
{{::exec(var_dump($ci4 === $ci);/*pad(54)*/)}} // {{eval}}

```

And as the opposite you can extract a raw `DateInterval` from `CarbonInterval` and even cast it in any class that extends `DateInterval`

```php
{{::lint($ci = CarbonInterval::days(2);)}}
{{::lint($di = $ci->toDateInterval();)}}
{{::exec(echo get_class($di);/*pad(22)*/)}} // '{{eval}}'
{{::exec(echo $di->d;/*pad(22)*/)}} // {{eval}}

// Your custom class can also extends CarbonInterval
{{::lint(class CustomDateInterval extends \DateInterval {})}}

{{::lint($di = $ci->cast(CustomDateInterval::class);)}}
{{::exec(echo get_class($di);/*pad(22)*/)}} // '{{eval}}'
{{::exec(echo $di->d;/*pad(22)*/)}} // {{eval}}

```

You can compare intervals the same way than Carbon objects, using `equalTo()`, `notEqualTo()` `lessThan()`, `lessThanOrEqualTo()`, `greaterThan()`, `greaterThanOrEqualTo()`, `between()`, `betweenExcluded()`, etc.

Other helpers, but beware the implementation provides helpers to handle weeks but only days are saved. Weeks are calculated based on the total days of the current instance.

```php
{{cihelper1::exec(
echo CarbonInterval::year()->years;/*pad(54)*/)}} // {{cihelper1_eval}}
{{::exec(echo CarbonInterval::year()->dayz;/*pad(54)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::days(24)->dayz;/*pad(54)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::days(24)->daysExcludeWeeks;/*pad(54)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::weeks(3)->days(14)->weeks;/*pad(54)*/)}} // {{eval}}  <-- days setter overwrites the current value
{{::exec(echo CarbonInterval::weeks(3)->addDays(14)->weeks;/*pad(54)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::weeks(3)->weeks;/*pad(54)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::minutes(3)->weeksAndDays(2, 5);/*pad(54)*/)}} // {{eval}}

```

CarbonInterval extends DateInterval and you can create both using [ISO-8601 duration format](https://en.wikipedia.org/wiki/ISO_8601#Durations):

```php
{{::lint($ci = CarbonInterval::create('P1Y2M3D');)}}
{{::exec(var_dump($ci->isEmpty());)}} // {{eval}}
{{::lint($ci = new CarbonInterval('PT0S');)}}
{{::exec(var_dump($ci->isEmpty());)}} // {{eval}}

```

Carbon intervals can be created from human-friendly strings thanks to `fromString()` method.

```php
{{::lint(CarbonInterval::fromString('2 minutes 15 seconds');
CarbonInterval::fromString('2m 15s'); // or abbreviated)}}

```

Or create it from an other `DateInterval` / `CarbonInterval` object.

```php
{{::lint($ci = new CarbonInterval(new \DateInterval('P1Y2M3D'));)}}
{{::exec(var_dump($ci->isEmpty());)}} // {{eval}}

```

Note that month abbreviate "mo" to distinguish from minutes and the whole syntax is not case sensitive.

It also has a handy `forHumans()`, which is mapped as the `__toString()` implementation, that prints the interval for humans.

```php
{{::lint(
CarbonInterval::setLocale('fr');)}}
{{::exec(echo CarbonInterval::create(2, 1)->forHumans();/*pad(54)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::hour()->addSeconds(3);/*pad(54)*/)}} // {{eval}}
{{::lint(CarbonInterval::setLocale('en');)}}

```

`forHumans($syntax, $short, $parts, $options)` allow the same option arguments as `Carbon::diffForHumans()` except `$parts` is set to -1 (no limit) by default. [See `Carbon::diffForHumans()` options.](#diff-for-humans-options)

As you can see, you can change the locale of the string using `CarbonInterval::setLocale('fr')`.

As for Carbon, you can use the make method to return a new instance of CarbonInterval from other interval or strings:

```php
{{::lint(
$dateInterval = new \DateInterval('P2D');
$carbonInterval = CarbonInterval::month();
)}}
{{::exec(echo CarbonInterval::make($dateInterval)->forHumans();/*pad(60)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::make($carbonInterval)->forHumans();/*pad(60)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::make(5, 'days')->forHumans();/*pad(60)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::make('PT3H')->forHumans();/*pad(60)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::make('1h 15m')->forHumans();/*pad(60)*/)}} // {{eval}}
// forHumans has many options, since version 2.9.0, the recommended way is to pass them as an associative array:
{{::exec(echo CarbonInterval::make('1h 15m')->forHumans(['short' => true]);/*pad(60)*/)}} // {{eval}}

// You can create interval from a string in any language:
{{::exec(echo CarbonInterval::parseFromLocale('3 jours et 2 heures', 'fr');/*pad(60)*/)}} // {{eval}}
// 'fr' stands for French but can be replaced with any locale code.
// if you don't pass the locale parameter, Carbon::getLocale() (current global locale) is used.

{{::lint($interval = CarbonInterval::make('1h 15m 45s');)}}
{{::exec(echo $interval->forHumans(['join' => true]);/*pad(60)*/)}} // {{eval}}
{{::lint($esInterval = CarbonInterval::make('1h 15m 45s');)}}
{{::exec(echo $esInterval->forHumans(['join' => true]);/*pad(60)*/)}} // {{eval}}
{{::exec(echo $interval->forHumans(['join' => true, 'parts' => 2]);/*pad(60)*/)}} // {{eval}}
{{::exec(echo $interval->forHumans(['join' => ' - ']);/*pad(60)*/)}} // {{eval}}

// Available syntax modes:
// ago/from now (translated in the current locale)
{{::exec(echo $interval->forHumans(['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW]);/*pad(84)*/)}} // {{eval}}
// before/after (translated in the current locale)
{{::exec(echo $interval->forHumans(['syntax' => CarbonInterface::DIFF_RELATIVE_TO_OTHER]);/*pad(84)*/)}} // {{eval}}
// default for intervals (no prefix/suffix):
{{::exec(echo $interval->forHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]);/*pad(84)*/)}} // {{eval}}

// Available options:
// transform empty intervals into "just now":
{{::exec(echo CarbonInterval::hours(0)->forHumans([
	'options' => CarbonInterface::JUST_NOW,
	'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
]);/*pad(84)*/)}} // {{eval}}
// transform empty intervals into "1 second":
{{::exec(echo CarbonInterval::hours(0)->forHumans([
	'options' => CarbonInterface::NO_ZERO_DIFF,
	'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
]);/*pad(84)*/)}} // {{eval}}
// transform "1 day ago"/"1 day from now" into "yesterday"/"tomorrow":
{{::exec(echo CarbonInterval::day()->forHumans([
	'options' => CarbonInterface::ONE_DAY_WORDS,
	'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
]);/*pad(84)*/)}} // {{eval}}
// transform "2 days ago"/"2 days from now" into "before yesterday"/"after tomorrow":
{{::exec(echo CarbonInterval::days(2)->forHumans([
	'options' => CarbonInterface::TWO_DAY_WORDS,
	'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
]);/*pad(84)*/)}} // {{eval}}
// options can be piped:
{{::exec(echo CarbonInterval::days(2)->forHumans([
	'options' => CarbonInterface::ONE_DAY_WORDS | CarbonInterface::TWO_DAY_WORDS,
	'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
]);/*pad(84)*/)}} // {{eval}}

// Before version 2.9.0, parameters could only be passed sequentially:
// $interval->forHumans($syntax, $short, $parts, $options)
// and join parameter was not available

```

The add, sub (or subtract), times, shares, multiply and divide methods allow to do proceed intervals calculations:

```php
{{::lint(
$interval = CarbonInterval::make('7h 55m');
$interval->add(CarbonInterval::make('17h 35m'));
$interval->subtract(10, 'minutes');
// add(), sub() and subtract() can take DateInterval, CarbonInterval, interval as string or 2 arguments factor and unit
$interval->times(3);
)}}
{{::exec(echo $interval->forHumans();/*pad(66)*/)}} // {{eval}}
echo "\n";
{{::lint(
$interval->shares(7);
)}}
{{::exec(echo $interval->forHumans();/*pad(66)*/)}} // {{eval}}
echo "\n";
// As you can see add(), times() and shares() operate naively a rounded calculation on each unit

// You also can use multiply() of divide() to cascade units and get precise calculation:
{{::exec(echo CarbonInterval::make('19h 55m')->multiply(3)->forHumans();/*pad(66)*/)}} // {{eval}}
echo "\n";
{{::exec(echo CarbonInterval::make('19h 55m')->divide(3)->forHumans();/*pad(66)*/)}} // {{eval}}

```

You get pure calculation from your input unit by unit. To cascade minutes into hours, hours into days etc. Use the cascade method:

```php
{{::exec(echo $interval->forHumans();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $interval->cascade()->forHumans();/*pad(40)*/)}} // {{eval}}

```

Default factors are:

*   1 minute = 60 seconds
*   1 hour = 60 minutes
*   1 day = 24 hour
*   1 week = 7 days
*   1 month = 4 weeks
*   1 year = 12 months

CarbonIntervals do not carry context so they cannot be more precise (no DST, no leap year, no real month length or year length consideration). But you can completely customize those factors. For example to deal with work time logs:

```php
{{::lint(
$cascades = CarbonInterval::getCascadeFactors(); // save initial factors

CarbonInterval::setCascadeFactors([
	'minute' => [60, 'seconds'],
	'hour' => [60, 'minutes'],
	'day' => [8, 'hours'],
	'week' => [5, 'days'],
	// in this example the cascade won't go farther than week unit
]);
)}}

{{::exec(echo CarbonInterval::fromString('20h')->cascade()->forHumans();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::fromString('10d')->cascade()->forHumans();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::fromString('3w 18d 53h 159m')->cascade()->forHumans();/*pad(76)*/)}} // {{eval}}

// You can see currently set factors with getFactor:
{{::exec(echo CarbonInterval::getFactor('minutes', /* per */ 'hour');/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::getFactor('days', 'week');/*pad(76)*/)}} // {{eval}}

// And common factors can be get with short-cut methods:
{{::exec(echo CarbonInterval::getDaysPerWeek();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::getHoursPerDay();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::getMinutesPerHour();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::getSecondsPerMinute();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::getMillisecondsPerSecond();/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::getMicrosecondsPerMillisecond();/*pad(76)*/)}} // {{eval}}

{{::lint(
CarbonInterval::setCascadeFactors($cascades); // restore original factors
)}}

```

Is it possible to convert an interval into a given unit (using provided cascade factors).

```php
{{::exec(echo CarbonInterval::days(3)->addHours(5)->total('hours');/*pad(58)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::days(3)->addHours(5)->totalHours;/*pad(58)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::months(6)->totalWeeks;/*pad(58)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::year()->totalDays;/*pad(58)*/)}} // {{eval}}

```

`->total` method and properties need cascaded intervals, if your interval can have overflow, cascade them before calling these feature:

```php
{{::exec(echo CarbonInterval::minutes(1200)->cascade()->total('hours');/*pad(58)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::minutes(1200)->cascade()->totalHours;/*pad(58)*/)}} // {{eval}}

```

You can also get the ISO 8601 spec of the inverval with `spec()`

```php
{{::exec(echo CarbonInterval::days(3)->addHours(5)->spec();)}} // {{eval}}
// By default microseconds are trimmed (as they would fail to recreate a proper DateInterval)
{{::exec(echo CarbonInterval::days(3)->addSeconds(5)->addMicroseconds(987654)->spec();)}} // {{eval}}
// But you can explicitly add them:
{{::exec(echo CarbonInterval::days(3)->addSeconds(5)->addMicroseconds(987654)->spec(true);)}} // {{eval}}

```

It's also possible to get it from a DateInterval object since to the static helper:

```php
{{::exec(echo CarbonInterval::getDateIntervalSpec(new \DateInterval('P3DT6M10S'));)}} // {{eval}}

```

List of date intervals can be sorted thanks to the `compare()` and `compareDateIntervals()` methods:

```php
{{::lint(
$halfDay = CarbonInterval::hours(12);
$oneDay = CarbonInterval::day();
$twoDay = CarbonInterval::days(2);
)}}

{{::exec(echo CarbonInterval::compareDateIntervals($oneDay, $oneDay);/*pad(62)*/)}} // {{eval}}
{{::exec(echo $oneDay->compare($oneDay);/*pad(62)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::compareDateIntervals($oneDay, $halfDay);/*pad(62)*/)}} // {{eval}}
{{::exec(echo $oneDay->compare($halfDay);/*pad(62)*/)}} // {{eval}}
{{::exec(echo CarbonInterval::compareDateIntervals($oneDay, $twoDay);/*pad(62)*/)}} // {{eval}}
{{::exec(echo $oneDay->compare($twoDay);/*pad(62)*/)}} // {{eval}}

{{::lint(
$list = [$twoDay, $halfDay, $oneDay];
usort($list, ['Carbon\CarbonInterval', 'compareDateIntervals']);
)}}

{{::exec(echo implode(', ', $list);/*pad(62)*/)}} // {{eval}}

```

Alternatively to fixed intervals, Dynamic intervals can be described with a function to step from a date to an other date:

```php
{{::lint(
$weekDayInterval = new CarbonInterval(function (CarbonInterface $date, bool $negated): \DateTime {
	// $negated is true when a subtraction is requested, false when an addition is requested
	return $negated
		? $date->subWeekday()
		: $date->addWeekday();
});
)}}

{{::exec(echo Carbon::parse('Wednesday')->sub($weekDayInterval)->dayName;)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::parse('Friday')->add($weekDayInterval)->dayName;)}} // {{eval}}
echo "\n";

{{::exec(foreach (Carbon::parse('2020-06-01')->range('2020-06-17', $weekDayInterval) as $date) {
	// every week day
	echo ' '.$date->day;
})}}
//{{eval}}

```

You can access and modify the closure step definition using `getStep()` and `setStep()` (the setter can take `null` to remove it so it becomes a simple fixed interval. And as long as the interval has a step, it will take the precedence over all fixed units it contains.

You can call `convertDate()` to apply the current dynamic or static interval to a date (`DateTime`, `Carbon` or immutable ones) positively (or negatively passing `true` as a second argument):

```php
{{::exec(echo $weekDayInterval->convertDate(new \DateTime('Wednesday'), true)->dayName;)}} // {{eval}}
{{::exec(echo $weekDayInterval->convertDate(new \DateTime('Friday'))->dayName;)}} // {{eval}}

```

Dump interval values as an array with:

```php
{{::lint(
$interval = CarbonInterval::months(2)->addHours(12)->addSeconds(50);
)}}

// All the values:
{{::exec(print_r($interval->toArray());)}}
/*
{{eval}}
*/

// Values sequence from the biggest to the smallest non-zero ones:
{{::exec(print_r($interval->getValuesSequence());)}}
/*
{{eval}}
*/

// Non-zero values:
{{::exec(print_r($interval->getNonZeroValues());)}}
/*
{{eval}}
*/

```

Last, a CarbonInterval instance can be converted into a CarbonPeriod instance by calling `toPeriod()` with complementary arguments.

I hear you ask what is a CarbonPeriod instance. Oh! Perfect transition to our next chapter.
