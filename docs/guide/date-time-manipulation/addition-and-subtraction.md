# Addition and Subtraction

The default DateTime provides a couple of different methods for easily adding and subtracting time. There is `modify()`, `add()` and `sub()`. `change()` is an enhanced version of `modify()` that can take _magical_ date/time format string, `'last day of next month'`, that it parses and applies the modification while `add()` and `sub()` can take the same the same kind of string, intervals (`DateInterval` or `CarbonInterval`) or count+unit parameters. But you can still access the native methods of `DateTime` class using `rawAdd()` and `rawSub()`.

```php
{{::lint($dt = Carbon::create(2012, 1, 31, 0);)}}

{{::exec(echo $dt->toDateTimeString();/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addCenturies(5);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addCentury();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subCentury();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subCenturies(5);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addYears(5);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addYear();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subYear();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subYears(5);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addQuarters(2);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addQuarter();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subQuarter();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subQuarters(2);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addMonths(60);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addMonth();/*pad(40)*/)}} // {{eval}} equivalent of $dt->month($dt->month + 1); so it wraps
{{::exec(echo $dt->subMonth();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMonths(60);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addDays(29);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addDay();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subDay();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subDays(29);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addWeekdays(4);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addWeekday();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subWeekday();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subWeekdays(4);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addWeeks(3);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addWeek();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subWeek();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subWeeks(3);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addHours(24);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addHour();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subHour();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subHours(24);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addMinutes(61);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addMinute();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMinute();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMinutes(61);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addSeconds(61);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addSecond();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subSecond();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subSeconds(61);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addMilliseconds(61);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addMillisecond();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMillisecond();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMillisecond(61);/*pad(40)*/)}} // {{eval}}

{{::exec(echo $dt->addMicroseconds(61);/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->addMicrosecond();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMicrosecond();/*pad(40)*/)}} // {{eval}}
{{::exec(echo $dt->subMicroseconds(61);/*pad(40)*/)}} // {{eval}}

// and so on for any unit: millenium, century, decade, year, quarter, month, week, day, weekday,
// hour, minute, second, microsecond.

// Generic methods add/sub (or subtract alias) can take many different arguments:
{{::exec(echo $dt->add(61, 'seconds');/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->sub('1 day');/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->add(CarbonInterval::months(2));/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->subtract(new \DateInterval('PT1H'));/*pad(50)*/)}} // {{eval}}

```

For fun you can also pass negative values to `addXXX()`, in fact that's how `subXXX()` is implemented.

P.S. Don't worry if you forget and use `addDay(5)` or `subYear(3)`, I have your back ;)

By default, Carbon relies on the underlying parent class PHP DateTime behavior. As a result adding or subtracting months can overflow, example:

```php
{{::lint($dt = CarbonImmutable::create(2017, 1, 31, 0);)}}

{{::exec(echo $dt->addMonth();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->subMonths(2);/*pad(40)*/)}} // {{eval}}

```

Since Carbon 2, you can set a local overflow behavior for each instance:

```php
{{::lint($dt = CarbonImmutable::create(2017, 1, 31, 0);
$dt->settings([
	'monthOverflow' => false,
]);
)}}

{{::exec(echo $dt->addMonth();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->subMonths(2);/*pad(40)*/)}} // {{eval}}

```

This will apply for methods `addMonth(s)`, `subMonth(s)`, `add($x, 'month')`, `sub($x, 'month')` and equivalent quarter methods. But it won't apply for intervals objects or strings like `add(CarbonInterval::month())` or `add('1 month')`.

Static helpers exist but are deprecated. If you're sure to need to apply global setting or work with version 1 of Carbon, [check the overflow static helpers section](#overflow-static-helpers)

You can prevent the overflow with `Carbon::useMonthsOverflow(false)`

```php
{{::lint(Carbon::useMonthsOverflow(false);

$dt = Carbon::createMidnightDate(2017, 1, 31);)}}

{{::exec(echo $dt->copy()->addMonth();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->copy()->subMonths(2);/*pad(40)*/)}} // {{eval}}

// Call the method with true to allow overflow again
{{reset1::exec(Carbon::resetMonthsOverflow();)}} // same as Carbon::useMonthsOverflow(true);

```

The method `Carbon::shouldOverflowMonths()` allows you to know if the overflow is currently enabled.

```php
{{::lint(Carbon::useMonthsOverflow(false);

$dt = Carbon::createMidnightDate(2017, 1, 31);)}}

{{::exec(echo $dt->copy()->addMonthWithOverflow();/*pad(50)*/)}} // {{eval}}
// plural addMonthsWithOverflow() method is also available
{{::exec(echo $dt->copy()->subMonthsWithOverflow(2);/*pad(50)*/)}} // {{eval}}
// singular subMonthWithOverflow() method is also available
{{::exec(echo $dt->copy()->addMonthNoOverflow();/*pad(50)*/)}} // {{eval}}
// plural addMonthsNoOverflow() method is also available
{{::exec(echo $dt->copy()->subMonthsNoOverflow(2);/*pad(50)*/)}} // {{eval}}
// singular subMonthNoOverflow() method is also available

{{::exec(echo $dt->copy()->addMonth();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonths(2);/*pad(50)*/)}} // {{eval}}

{{::lint(Carbon::useMonthsOverflow(true);

$dt = Carbon::createMidnightDate(2017, 1, 31);)}}

{{::exec(echo $dt->copy()->addMonthWithOverflow();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonthsWithOverflow(2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->addMonthNoOverflow();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonthsNoOverflow(2);/*pad(50)*/)}} // {{eval}}

{{::exec(echo $dt->copy()->addMonth();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonths(2);/*pad(50)*/)}} // {{eval}}

{{reset2::exec(Carbon::resetMonthsOverflow();)}}

```

From version 1.23.0, overflow control is also available on years:

```php
{{::lint(Carbon::useYearsOverflow(false);

$dt = Carbon::createMidnightDate(2020, 2, 29);)}}

{{::exec(var_dump(Carbon::shouldOverflowYears());/*pad(50)*/)}} // {{eval}}

{{::exec(echo $dt->copy()->addYearWithOverflow();/*pad(50)*/)}} // {{eval}}
// plural addYearsWithOverflow() method is also available
{{::exec(echo $dt->copy()->subYearsWithOverflow(2);/*pad(50)*/)}} // {{eval}}
// singular subYearWithOverflow() method is also available
{{::exec(echo $dt->copy()->addYearNoOverflow();/*pad(50)*/)}} // {{eval}}
// plural addYearsNoOverflow() method is also available
{{::exec(echo $dt->copy()->subYearsNoOverflow(2);/*pad(50)*/)}} // {{eval}}
// singular subYearNoOverflow() method is also available

{{::exec(echo $dt->copy()->addYear();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subYears(2);/*pad(50)*/)}} // {{eval}}

{{::lint(Carbon::useYearsOverflow(true);

$dt = Carbon::createMidnightDate(2020, 2, 29);)}}

{{::exec(var_dump(Carbon::shouldOverflowYears());/*pad(50)*/)}} // {{eval}}

{{::exec(echo $dt->copy()->addYearWithOverflow();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subYearsWithOverflow(2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->addYearNoOverflow();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subYearsNoOverflow(2);/*pad(50)*/)}} // {{eval}}

{{::exec(echo $dt->copy()->addYear();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subYears(2);/*pad(50)*/)}} // {{eval}}

{{reset3::exec(Carbon::resetYearsOverflow();)}}

```

You also can use `->addMonthsNoOverflow`, `->subMonthsNoOverflow`, `->addMonthsWithOverflow` and `->subMonthsWithOverflow` (or the singular methods with no `s` to "month") to explicitly add/sub months with or without overflow no matter the current mode and the same for any bigger unit (quarter, year, decade, century, millennium).

```php
{{::lint($dt = Carbon::createMidnightDate(2017, 1, 31)->settings([
	'monthOverflow' => false,
]);)}}

{{::exec(echo $dt->copy()->addMonthWithOverflow();/*pad(50)*/)}} // {{eval}}
// plural addMonthsWithOverflow() method is also available
{{::exec(echo $dt->copy()->subMonthsWithOverflow(2);/*pad(50)*/)}} // {{eval}}
// singular subMonthWithOverflow() method is also available
{{::exec(echo $dt->copy()->addMonthNoOverflow();/*pad(50)*/)}} // {{eval}}
// plural addMonthsNoOverflow() method is also available
{{::exec(echo $dt->copy()->subMonthsNoOverflow(2);/*pad(50)*/)}} // {{eval}}
// singular subMonthNoOverflow() method is also available

{{::exec(echo $dt->copy()->addMonth();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonths(2);/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::createMidnightDate(2017, 1, 31)->settings([
	'monthOverflow' => true,
]);)}}

{{::exec(echo $dt->copy()->addMonthWithOverflow();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonthsWithOverflow(2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->addMonthNoOverflow();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonthsNoOverflow(2);/*pad(50)*/)}} // {{eval}}

{{::exec(echo $dt->copy()->addMonth();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->copy()->subMonths(2);/*pad(50)*/)}} // {{eval}}

```

The same is available for years.

You also can control overflow for any unit when working with unknown inputs:

```php
{{::lint($dt = CarbonImmutable::create(2018, 8, 30, 12, 00, 00);)}}

// Add hours without overflowing day
{{::exec(echo $dt->addUnitNoOverflow('hour', 7, 'day');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->addUnitNoOverflow('hour', 14, 'day');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->addUnitNoOverflow('hour', 48, 'day');/*pad(50)*/)}} // {{eval}}

echo "\n-------\n";

// Substract hours without overflowing day
{{::exec(echo $dt->subUnitNoOverflow('hour', 7, 'day');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->subUnitNoOverflow('hour', 14, 'day');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->subUnitNoOverflow('hour', 48, 'day');/*pad(50)*/)}} // {{eval}}

echo "\n-------\n";

// Set hours without overflowing day
{{::exec(echo $dt->setUnitNoOverflow('hour', -7, 'day');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->setUnitNoOverflow('hour', 14, 'day');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->setUnitNoOverflow('hour', 25, 'day');/*pad(50)*/)}} // {{eval}}

echo "\n-------\n";

// Adding hours without overflowing month
{{::exec(echo $dt->addUnitNoOverflow('hour', 7, 'month');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->addUnitNoOverflow('hour', 14, 'month');/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $dt->addUnitNoOverflow('hour', 48, 'month');/*pad(50)*/)}} // {{eval}}

```

Any modifiable unit can be passed as argument of those methods:

```php
{{::lint($units = [];
foreach (['millennium', 'century', 'decade', 'year', 'quarter', 'month', 'week', 'weekday', 'day', 'hour', 'minute', 'second', 'millisecond', 'microsecond'] as $unit) {
	$units[$unit] = Carbon::isModifiableUnit($unit);
}
)}}

{{::exec(echo json_encode($units, JSON_PRETTY_PRINT);)}}
/* {{eval}} */

```
