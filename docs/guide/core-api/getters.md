---
order: 1
---

# Getters

The getters are implemented via PHP's `__get()` method. This enables you to access the value as if it was a property rather than a function call.

```php
{{::lint($dt = Carbon::parse('2012-10-5 23:26:11.123789');)}}

// These getters specifically return integers, ie intval()
{{::exec(var_dump($dt->year);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->month);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->day);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->hour);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->minute);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->second);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->micro);/*pad(60)*/)}} // {{eval}}
// dayOfWeek returns a number between 0 (sunday) and 6 (saturday)
{{::exec(var_dump($dt->dayOfWeek);/*pad(60)*/)}} // {{eval}}
// dayOfWeekIso returns a number between 1 (monday) and 7 (sunday)
{{::exec(var_dump($dt->dayOfWeekIso);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->englishDayOfWeek);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->shortEnglishDayOfWeek);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->locale('de')->dayName);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->locale('de')->shortDayName);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->locale('de')->minDayName);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->englishMonth);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->shortEnglishMonth);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->locale('de')->monthName);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->locale('de')->shortMonthName);/*pad(60)*/)}} // {{eval}}

{{::exec(var_dump($dt->dayOfYear);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->weekNumberInMonth);/*pad(60)*/)}} // {{eval}}
// weekNumberInMonth consider weeks from monday to sunday, so the week 1 will
// contain 1 day if the month start with a sunday, and up to 7 if it starts with a monday
{{::exec(var_dump($dt->weekOfMonth);/*pad(60)*/)}} // {{eval}}
// weekOfMonth will returns 1 for the 7 first days of the month, then 2 from the 8th to
// the 14th, 3 from the 15th to the 21st, 4 from 22nd to 28th and 5 above
{{::exec(var_dump($dt->weekOfYear);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->daysInMonth);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->timestamp);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump($dt->getTimestamp());/*pad(60)*/)}} // {{eval}}
// Millisecond-precise timestamp as int
{{::exec(var_dump($dt->getTimestampMs());/*pad(60)*/)}} // {{eval}}
// Millisecond-precise timestamp as float (useful to pass it to JavaScript)
{{::exec(var_dump($dt->valueOf());/*pad(60)*/)}} // {{eval}}
// Custom-precision timestamp
{{::exec(var_dump($dt->getPreciseTimestamp(6));/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromDate(1975, 5, 21)->age);/*pad(60)*/)}} // {{eval}} calculated vs now in the same tz
{{::exec(var_dump($dt->quarter);/*pad(60)*/)}} // {{eval}}

// Returns an int of seconds difference from UTC (+/- sign included)
{{::exec(var_dump(Carbon::createFromTimestampUTC(0)->offset);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromTimestamp(0, 'Europe/Paris')->offset);/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromTimestamp(0, 'Europe/Paris')->getOffset());/*pad(80)*/)}} // {{eval}}

// Returns an int of hours difference from UTC (+/- sign included)
{{::exec(var_dump(Carbon::createFromTimestamp(0, 'Europe/Paris')->offsetMinutes);/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromTimestamp(0, 'Europe/Paris')->offsetHours);/*pad(80)*/)}} // {{eval}}

// Returns timezone offset as string
{{::exec(var_dump(Carbon::createFromTimestamp(0, 'Europe/Paris')->getOffsetString());/*pad(80)*/)}} // {{eval}}

// Returns timezone as CarbonTimeZone
{{::exec(var_dump(Carbon::createFromTimestamp(0, 'Europe/Paris')->getTimezone());)}}
/* {{eval}} */

// Indicates if day light savings time is on
{{::exec(var_dump(Carbon::createFromDate(2012, 1, 1)->dst);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromDate(2012, 9, 1)->dst);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromDate(2012, 9, 1)->isDST());/*pad(60)*/)}} // {{eval}}

// Indicates if the instance is in the same timezone as the local timezone
{{::exec(var_dump(Carbon::now()->local);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::now('America/Vancouver')->local);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::now()->isLocal());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::now('America/Vancouver')->isLocal());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::now()->isUtc());/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::now('America/Vancouver')->isUtc());/*pad(60)*/)}} // {{eval}}
// can also be written ->isUTC()

// Indicates if the instance is in the UTC timezone
{{::exec(var_dump(Carbon::now()->utc);/*pad(60)*/)}} // {{eval}}
// London is not UTC on summer time
{{::exec(var_dump(Carbon::parse('2018-10-01', 'Europe/London')->utc);/*pad(60)*/)}} // {{eval}}
// London is UTC on winter time
{{::exec(var_dump(Carbon::parse('2018-11-01', 'Europe/London')->utc);/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::createFromTimestampUTC(0)->utc);/*pad(60)*/)}} // {{eval}}

// Gets the DateTimeZone instance
{{::exec(echo get_class(Carbon::now()->timezone);/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo get_class(Carbon::now()->tz);/*pad(60)*/)}} // {{eval}}
echo "\n";

// Gets the DateTimeZone instance name, shortcut for ->timezone->getName()
{{::exec(echo Carbon::now()->timezoneName;/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->tzName;/*pad(60)*/)}} // {{eval}}
echo "\n";

{{::lint(// You can get any property dynamically too:
$unit = 'second';)}}
{{::exec(var_dump(Carbon::now()->get($unit));/*pad(60)*/)}} // {{eval}}
// equivalent to:
{{::exec(var_dump(Carbon::now()->$unit);/*pad(60)*/)}} // {{eval}}
{{::lint(// If you have plural unit name, use singularUnit()
$unit = Carbon::singularUnit('seconds');)}}
{{::exec(var_dump(Carbon::now()->get($unit));/*pad(60)*/)}} // {{eval}}
// Prefer using singularUnit() because some plurals are not the word with S:
{{::exec(var_dump(Carbon::pluralUnit('century'));/*pad(60)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::pluralUnit('millennium'));/*pad(60)*/)}} // {{eval}}

```
