# Modifiers

These group of methods perform helpful modifications to the current instance. Most of them are self explanatory from their names... or at least should be. You'll also notice that the startOfXXX(), next() and previous() methods set the time to 00:00:00 and the endOfXXX() methods set the time to 23:59:59 for unit bigger than days.

The only one slightly different is the `average()` function. It moves your instance to the middle date between itself and the provided Carbon argument.

The powerful native [`modify()` method of `DateTime`](https://www.php.net/manual/en/datetime.modify.php) is available untouched. But we also provide an enhanced version of it: `change()` that allows some additional syntax like `"next 3pm"` and that is called internally by `->next()` and `->previous()`.

```php
{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->startOfSecond()->format('s.u');/*pad(50)*/)}} // {{eval}}


{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->endOfSecond()->format('s.u');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->startOf('second')->format('s.u');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->endOf('second')->format('s.u');/*pad(50)*/)}} // {{eval}}
// ->startOf() and ->endOf() are dynamic equivalents to those methods

{{::lint($dt = Carbon::create(2012, 1, 31, 15, 32, 45);)}}
{{::exec(echo $dt->startOfMinute();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 15, 32, 45);)}}
{{::exec(echo $dt->endOfMinute();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 15, 32, 45);)}}
{{::exec(echo $dt->startOfHour();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 15, 32, 45);)}}
{{::exec(echo $dt->endOfHour();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 15, 32, 45);)}}
{{::exec(echo Carbon::getMidDayAt();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->midDay();/*pad(50)*/)}} // {{eval}}
{{::lint(Carbon::setMidDayAt(13);)}}
{{::exec(echo Carbon::getMidDayAt();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->midDay();/*pad(50)*/)}} // {{eval}}
{{::lint(Carbon::setMidDayAt(12);)}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->startOfDay();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->endOfDay();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->startOfMonth();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->endOfMonth();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->startOfYear();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->endOfYear();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->startOfDecade();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->endOfDecade();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->startOfCentury();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->endOfCentury();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->startOfWeek();/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($dt->dayOfWeek == Carbon::MONDAY);/*pad(50)*/)}} // {{eval}} : ISO8601 week starts on Monday

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->endOfWeek();/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($dt->dayOfWeek == Carbon::SUNDAY);/*pad(50)*/)}} // {{eval}} : ISO8601 week ends on Sunday

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->next(Carbon::WEDNESDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($dt->dayOfWeek == Carbon::WEDNESDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->next('Wednesday');/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->next('04:00');/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->next('12:00');/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->next('04:00');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 1, 12, 0, 0);)}}
{{::exec(echo $dt->next();/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 31, 12, 0, 0);)}}
{{::exec(echo $dt->previous(Carbon::WEDNESDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($dt->dayOfWeek == Carbon::WEDNESDAY);/*pad(50)*/)}} // {{eval}}

{{::lint($dt = Carbon::create(2012, 1, 1, 12, 0, 0);)}}
{{::exec(echo $dt->previous();/*pad(50)*/)}} // {{eval}}

{{::lint($start = Carbon::create(2014, 1, 1, 0, 0, 0);)}}
{{::lint($end = Carbon::create(2014, 1, 30, 0, 0, 0);)}}
{{::exec(echo $start->average($end);/*pad(50)*/)}} // {{eval}}

{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->firstOfMonth();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->firstOfMonth(Carbon::MONDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->lastOfMonth();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->lastOfMonth(Carbon::TUESDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->nthOfMonth(2, Carbon::SATURDAY);/*pad(80)*/)}} // {{eval}}

{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->firstOfQuarter();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->firstOfQuarter(Carbon::MONDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->lastOfQuarter();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->lastOfQuarter(Carbon::TUESDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->nthOfQuarter(2, Carbon::SATURDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->startOfQuarter();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->endOfQuarter();/*pad(80)*/)}} // {{eval}}

{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->firstOfYear();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->firstOfYear(Carbon::MONDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->lastOfYear();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->lastOfYear(Carbon::TUESDAY);/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2014, 5, 30, 0, 0, 0)->nthOfYear(2, Carbon::SATURDAY);/*pad(80)*/)}} // {{eval}}

{{::exec(echo Carbon::create(2018, 2, 23, 0, 0, 0)->nextWeekday();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2018, 2, 23, 0, 0, 0)->previousWeekday();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2018, 2, 21, 0, 0, 0)->nextWeekendDay();/*pad(80)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2018, 2, 21, 0, 0, 0)->previousWeekendDay();/*pad(80)*/)}} // {{eval}}

```

Rounding is also available for any unit:

```php
{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->roundMillisecond()->format('H:i:s.u');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->roundSecond()->format('H:i:s.u');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->floorSecond()->format('H:i:s.u');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = new Carbon('2012-01-31 15:32:15');)}}
{{::exec(echo $dt->roundMinute()->format('H:i:s');/*pad(50)*/)}} // {{eval}}

{{::lint($dt = new Carbon('2012-01-31 15:32:15');)}}
{{::exec(echo $dt->ceilMinute()->format('H:i:s');/*pad(50)*/)}} // {{eval}}

// and so on up to millennia!

// precision rounding can be set, example: rounding to ten minutes
{{::lint($dt = new Carbon('2012-01-31 15:32:15');)}}
{{::exec(echo $dt->roundMinute(10)->format('H:i:s');/*pad(50)*/)}} // {{eval}}

// and round, floor and ceil methods are shortcut for second rounding:
{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->round()->format('H:i:s.u');/*pad(50)*/)}} // {{eval}}
{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->floor()->format('H:i:s.u');/*pad(50)*/)}} // {{eval}}
{{::lint($dt = new Carbon('2012-01-31 15:32:45.654321');)}}
{{::exec(echo $dt->ceil()->format('H:i:s.u');/*pad(50)*/)}} // {{eval}}

// you can also pass the unit dynamically (and still precision as second argument):
{{::lint($dt = new Carbon('2012-01-31');)}}
{{::exec(echo $dt->roundUnit('month', 2)->format('Y-m-d');/*pad(50)*/)}} // {{eval}}
{{::lint($dt = new Carbon('2012-01-31');)}}
{{::exec(echo $dt->floorUnit('month')->format('Y-m-d');/*pad(50)*/)}} // {{eval}}
{{::lint($dt = new Carbon('2012-01-31');)}}
{{::exec(echo $dt->ceilUnit('month', 4)->format('Y-m-d');/*pad(50)*/)}} // {{eval}}

```
