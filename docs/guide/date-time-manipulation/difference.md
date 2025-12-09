# Difference

`Carbon` `diff()` and `diffAsCarbonInterval()` methods return a `CarbonInterval` (since Carbon v3, while it returned `DateInterval` in the previous versions).

Check [CarbonInterval chapter](#api-interval) for more information.

We also provide `diffAsDateInterval()` act like `diff()` but returns a `DateInterval` instance.

Carbon add diff methods for each unit too, such as `diffInYears()`, `diffInMonths()` and so on. `diffAsCarbonInterval()` and `diffIn*()` (and `floatDiffIn*()` for versions < 3 when `diffIn*()` methods returned integer values, since Carbon 3, they are deprecated as `diffIn*()` already return floating number, and integer values from it can easily be obtained with an explicit cast `(int)`).

`diffInUnit()` allow to get a diff for a unit calculated dynamically: `->diffInUnit($unit, $date, $absolute)`

All can take 2 optional arguments: date to compare with (if missing, now is used instead), and an absolute boolean option (`false` by default), it returns negative value when the instance the method is called on is greater than the compared date (first argument or now). If set to `true`, that makes the method return an absolute value no matter which date is greater than the other.

```php
{{::exec(echo Carbon::now('America/Vancouver')->diffInSeconds(Carbon::now('Europe/London'));)}} // {{eval}}

{{::lint($dtOttawa = Carbon::createMidnightDate(2000, 1, 1, 'America/Toronto');)}}
{{::lint($dtVancouver = Carbon::createMidnightDate(2000, 1, 1, 'America/Vancouver');)}}
{{::exec(echo $dtOttawa->diffInHours($dtVancouver);/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dtVancouver->diffInHours($dtOttawa);/*pad(70)*/)}} // {{eval}}

{{::exec(echo $dtOttawa->diffInHours($dtVancouver, false);/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dtVancouver->diffInHours($dtOttawa, false);/*pad(70)*/)}} // {{eval}}

{{::lint($dt = Carbon::createMidnightDate(2012, 1, 31);)}}
{{::exec(echo $dt->diffInDays($dt->copy()->addMonth());/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dt->diffInDays($dt->copy()->subMonth(), false);/*pad(70)*/)}} // {{eval}}

{{::lint($dt = Carbon::createMidnightDate(2012, 4, 30);)}}
{{::exec(echo $dt->diffInDays($dt->copy()->addMonth());/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dt->diffInDays($dt->copy()->addWeek());/*pad(70)*/)}} // {{eval}}

{{::lint($dt = Carbon::createMidnightDate(2012, 1, 1);)}}
{{::exec(echo $dt->diffInMinutes($dt->copy()->addSeconds(59));/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dt->diffInMinutes($dt->copy()->addSeconds(60));/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dt->diffInMinutes($dt->copy()->addSeconds(119));/*pad(70)*/)}} // {{eval}}
{{::exec(echo $dt->diffInMinutes($dt->copy()->addSeconds(120));/*pad(70)*/)}} // {{eval}}

{{::exec(echo $dt->addSeconds(120)->secondsSinceMidnight();/*pad(70)*/)}} // {{eval}}

{{::lint($interval = $dt->diffAsCarbonInterval($dt->copy()->subYears(3), false);)}}
// diffAsCarbonInterval use same arguments as diff($other, $absolute)
// (native method from \DateTime)
// except $absolute is true by default for diffAsCarbonInterval and false for diff
// $absolute parameter allow to get signed value if false, or always positive if true
{{::exec(echo ($interval->invert ? 'minus ' : 'plus ') . $interval->years;/*pad(70)*/)}} // {{eval}}

```

These methods have int-truncated results. That means `diffInMinutes` returns 1 for any difference between 1 included and 2 excluded. But same methods are available for float results:

```php
{{::exec(echo Carbon::parse('06:01:23.252987')->floatDiffInSeconds('06:02:34.321450');/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('06:01:23')->floatDiffInMinutes('06:02:34');/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('06:01:23')->floatDiffInHours('06:02:34');/*pad(80)*/)}} // {{eval}}
// Those methods are absolute by default but can return negative value
// setting the second argument to false when start date is greater than end date
{{::exec(echo Carbon::parse('12:01:23')->floatDiffInHours('06:02:34', false);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('2000-01-01 12:00')->floatDiffInDays('2000-02-11 06:00');/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('2000-01-01')->floatDiffInWeeks('2000-02-11');/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('2000-01-15')->floatDiffInMonths('2000-02-24');/*pad(80)*/)}} // {{eval}}
// floatDiffInMonths count as many full months as possible from the start date
// (for instance 31 days if the start is in January), then consider the number
// of days in the months for ending chunks to reach the end date.
// So the following result (ending with 24 march is different from previous one with 24 february):
{{::exec(echo Carbon::parse('2000-02-15 12:00')->floatDiffInMonths('2000-03-24 06:00');/*pad(80)*/)}} // {{eval}}
// floatDiffInYears apply the same logic (and so different results with leap years)
{{::exec(echo Carbon::parse('2000-02-15 12:00')->floatDiffInYears('2010-03-24 06:00');/*pad(80)*/)}} // {{eval}}

```

⚠️ Important note about the daylight saving times (DST), by default PHP DateTime does not take DST into account, that means for example that a day with only 23 hours like March the 30th 2014 in London will be counted as 24 hours long.

⚠️ Be careful of `floatDiffInMonths()` which can gives you a lower result (`number of months in A < number of months in B`) for an interval having more days (`number of days in A > number of days in B`) due to the variable number of days in months (especially February). By default, we rely on the result of [DateTime::diff](https://www.php.net/manual/en/datetime.diff.php) which is sensitive to overflow. [See issue #2264 for alternative calculations](https://github.com/briannesbitt/Carbon/issues/2264).

```php
{{::lint($date = new \DateTime('2014-03-30 00:00:00', new \DateTimeZone('Europe/London'));/*pad(65)*/)}} // DST off
{{::exec(echo $date->modify('+25 hours')->format('H:i');/*pad(65)*/)}} // {{eval}} (DST on, 24 hours only have been actually added)

```

Carbon follow this behavior too for add/sub/diff seconds/minutes/hours. But we provide methods to works with _real_ hours using timestamp:

```php
{{::lint($date = new Carbon('2014-03-30 00:00:00', 'Europe/London');/*pad(65)*/)}} // DST off
{{::exec(echo $date->addRealHours(25)->format('H:i');/*pad(65)*/)}} // {{eval}} (DST on)
{{::exec(echo $date->diffInRealHours('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInHours('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInRealMinutes('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInMinutes('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInRealSeconds('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInSeconds('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInRealMilliseconds('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInMilliseconds('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInRealMicroseconds('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->diffInMicroseconds('2014-03-30 00:00:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->subRealHours(25)->format('H:i');/*pad(65)*/)}} // {{eval}} (DST off)

// with float diff:
{{::lint($date = new Carbon('2019-10-27 00:00:00', 'Europe/Paris');)}}
{{::exec(echo $date->floatDiffInRealHours('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInHours('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInRealMinutes('2019-10-28 12:00:30');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInMinutes('2019-10-28 12:00:30');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInRealSeconds('2019-10-28 12:00:00.5');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInSeconds('2019-10-28 12:00:00.5');/*pad(65)*/)}} // {{eval}}
// above day unit, "real" will affect the decimal part based on hours and smaller units
{{::exec(echo $date->floatDiffInRealDays('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInDays('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInRealWeeks('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInWeeks('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInRealMonths('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInMonths('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInRealYears('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}
{{::exec(echo $date->floatDiffInYears('2019-10-28 12:30:00');/*pad(65)*/)}} // {{eval}}

```

The same way you can use `addRealX()` and `subRealX()` on any unit.

There are also special filter functions `diffInDaysFiltered()`, `diffInHoursFiltered()` and `diffFiltered()`, to help you filter the difference by days, hours or a custom interval. For example to count the weekend days between two instances:

```php
{{::lint(
$dt = Carbon::create(2014, 1, 1);
$dt2 = Carbon::create(2014, 12, 31);
$daysForExtraCoding = $dt->diffInDaysFiltered(function(Carbon $date) {
   return $date->isWeekend();
}, $dt2);
)}}

{{::exec(echo $daysForExtraCoding;/*pad(30)*/)}} // {{eval}}

{{::lint(
$dt = Carbon::create(2014, 1, 1)->endOfDay();
$dt2 = $dt->copy()->startOfDay();
$littleHandRotations = $dt->diffFiltered(CarbonInterval::minute(), function(Carbon $date) {
   return $date->minute === 0;
}, $dt2, true); // true as last parameter returns absolute value
)}}

{{::exec(echo $littleHandRotations;/*pad(30)*/)}} // {{eval}}

{{::lint($date = Carbon::now()->addSeconds(3666);)}}

{{::exec(echo $date->diffInSeconds();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date->diffInMinutes();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date->diffInHours();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date->diffInDays();/*pad(50)*/)}} // {{eval}}

{{::lint($date = Carbon::create(2016, 1, 5, 22, 40, 32);)}}

{{::exec(echo $date->secondsSinceMidnight();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date->secondsUntilEndOfDay();/*pad(50)*/)}} // {{eval}}

{{::lint(
$date1 = Carbon::createMidnightDate(2016, 1, 5);
$date2 = Carbon::createMidnightDate(2017, 3, 15);
)}}

{{::exec(echo $date1->diffInDays($date2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date1->diffInWeekdays($date2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date1->diffInWeekendDays($date2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date1->diffInWeeks($date2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date1->diffInMonths($date2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date1->diffInQuarters($date2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $date1->diffInYears($date2);/*pad(50)*/)}} // {{eval}}

```

All diffIn\*Filtered method take 1 callable filter as required parameter and a date object as optional second parameter, if missing, now is used. You may also pass true as third parameter to get absolute values.

For advanced handle of the week/weekend days, use the following tools:

```php
{{::exec(echo implode(', ', Carbon::getDays());/*pad(60)*/)}} // {{eval}}

{{::lint(
$saturday = new Carbon('first saturday of 2019');
$sunday = new Carbon('first sunday of 2019');
$monday = new Carbon('first monday of 2019');
)}}

{{::exec(echo implode(', ', Carbon::getWeekendDays());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($saturday->isWeekend());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($sunday->isWeekend());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($monday->isWeekend());/*pad(60)*/)}} // {{eval}}

{{::lint(
Carbon::setWeekendDays([
	Carbon::SUNDAY,
	Carbon::MONDAY,
]);
)}}

{{::exec(echo implode(', ', Carbon::getWeekendDays());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($saturday->isWeekend());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($sunday->isWeekend());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($monday->isWeekend());/*pad(60)*/)}} // {{eval}}

{{::lint(
Carbon::setWeekendDays([
	Carbon::SATURDAY,
	Carbon::SUNDAY,
]);
// weekend days and start/end of week or not linked
// start/end of week are driven by the locale
)}}

{{::exec(var_dump(Carbon::getWeekStartsAt());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::getWeekEndsAt());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::getWeekStartsAt('ar_EG'));/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::getWeekEndsAt('ar_EG'));/*pad(60)*/)}} // {{eval}}

```
