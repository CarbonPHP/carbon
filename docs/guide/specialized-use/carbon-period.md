# CarbonPeriod

CarbonPeriod is a human-friendly version of the [DatePeriod](https://www.php.net/manual/en/class.dateperiod.php) with many shortcuts.

```php
{{::lint(
// Create a new instance:
$period = new CarbonPeriod('2018-04-21', '3 days', '2018-04-27');
// Use static constructor:
$period = CarbonPeriod::create('2018-04-21', '3 days', '2018-04-27');
// Use the fluent setters:
$period = CarbonPeriod::since('2018-04-21')->days(3)->until('2018-04-27');
// Start from a CarbonInterval:
$period = CarbonInterval::days(3)->toPeriod('2018-04-21', '2018-04-27');
// From a diff:
$period = Carbon::parse('2020-08-29')->diff('2020-09-02')->stepBy('day');
$period = Carbon::parse('2020-08-29')->diff('2020-09-02')->stepBy(12, 'hours');
// toPeriod can also be called from a Carbon or CarbonImmutable instance:
$period = Carbon::parse('2018-04-21')->toPeriod('2018-04-27', '3 days'); // pass end and interval
// interval can be a string, a DateInterval or a CarbonInterval
// You also can pass 2 arguments: number an string:
$period = Carbon::parse('2018-04-21')->toPeriod('2018-04-27', 3, 'days');
// Same as above:
$period = Carbon::parse('2018-04-21')->range('2018-04-27', 3, 'days'); // Carbon::range is an alias of Carbon::toPeriod
// Still the same:
$period = Carbon::parse('2018-04-21')->daysUntil('2018-04-27', 3);
// By default daysUntil will use a 1-day interval:
$period = Carbon::parse('2018-04-21')->daysUntil('2018-04-27'); // same as CarbonPeriod::create('2018-04-21', '1 day', '2018-04-27')
/*
    microsUntil() or microsecondsUntil() provide the same feature for microseconds intervals
    millisUntil() or millisecondsUntil() provide the same feature for milliseconds intervals
    secondsUntil() provides the same feature for seconds intervals
    minutesUntil() provides the same feature for minutes intervals
    hoursUntil() provides the same feature for hours intervals
    weeksUntil() provides the same feature for weeks intervals
    monthsUntil() provides the same feature for months intervals
    quartersUntil() provides the same feature for quarters intervals
    yearsUntil() provides the same feature for years intervals
    decadesUntil() provides the same feature for decades intervals
    centuriesUntil() provides the same feature for centuries intervals
    millenniaUntil() provides the same feature for millennia intervals
*/
// Using number of recurrences:
CarbonPeriod::create('now', '1 day', 3); // now, now + 1 day, now + 2 day
// Can be infinite:
CarbonPeriod::create('now', '2 days', INF); // infinite iteration
CarbonPeriod::create('now', '2 days', INF)->calculateEnd()->isEndOfTime(); // true
CarbonPeriod::create('now', CarbonInterval::days(-2), INF)->calculateEnd()->isStartOfTime(); // true
)}}

```

A CarbonPeriod can be constructed in a number of ways:

*   start date, end date and optional interval (by default 1 day),
*   start date, number of recurrences and optional interval,
*   an ISO 8601 interval specification,
*   from another `DatePeriod` or `CarbonPeriod` using `CarbonPeriod::instance($period)` or simply using `new CarbonPeriod($period)`.

Dates can be given as DateTime/Carbon instances, absolute strings like "2007-10-15 15:00" or relative strings, for example "next monday". Interval can be given as DateInterval/CarbonInterval instance, ISO 8601 interval specification like "P4D", or human readable string, for example "4 days".

Default constructor and `create()` methods are very forgiving in terms of argument types and order, so if you want to be more precise the fluent syntax is recommended. On the other hand you can pass dynamic array of values to `createFromArray()` which will do the job of constructing a new instance with the given array as a list of arguments.

CarbonPeriod implements the [Iterator](https://www.php.net/manual/en/class.iterator.php) interface. It means that it can be passed directly to a `foreach` loop:

```php
{{::lint(
$period = CarbonPeriod::create('2018-04-21', '3 days', '2018-04-27');
)}}
{{::exec(
foreach ($period as $key => $date) {
    if ($key) {
        echo ', ';
    }
    echo $date->format('m-d');
}
)}}
// {{eval}}
echo "\n";

// Here is what happens under the hood:
{{::exec(
$period->rewind(); // restart the iteration
while ($period->valid()) { // check if current item is valid
    if ($period->key()) { // echo comma if current key is greater than 0
        echo ', ';
    }
    echo $period->current()->format('m-d'); // echo current date
    $period->next(); // move to the next item
}
)}}
// {{eval}}

```

Parameters can be modified during the iteration:

```php
{{::lint(
$period = CarbonPeriod::create('2018-04-29', 7);
$dates = [];
foreach ($period as $key => $date) {
    if ($key === 3) {
        $period->invert()->start($date); // invert() is an alias for invertDateInterval()
    }
    $dates[] = $date->format('m-d');
}
)}}

{{::exec(echo implode(', ', $dates);)}} // {{eval}}

```

Just as DatePeriod, the CarbonPeriod supports [ISO 8601 time interval specification](https://en.wikipedia.org/wiki/ISO_8601#Time_intervals).

Note that the native DatePeriod treats recurrences as a number of times to repeat the interval. Thus it will give one less result when the start date is excluded. Introduction of custom filters in CarbonPeriod made it even more difficult to know the number of results. For that reason we changed the implementation slightly, and recurrences are treated as an overall limit for number of returned dates.

```php
{{::lint(
// Possible options are: CarbonPeriod::EXCLUDE_START_DATE | CarbonPeriod::EXCLUDE_END_DATE
// Default value is 0 which will have the same effect as when no options are given.
$period = CarbonPeriod::createFromIso('R4/2012-07-01T00:00:00Z/P7D', CarbonPeriod::EXCLUDE_START_DATE);
$dates = [];
foreach ($period as $date) {
    $dates[] = $date->format('m-d');
}
)}}

{{::exec(echo implode(', ', $dates);)}} // {{eval}}

```

You can retrieve data from the period with variety of getters:

```php
{{::lint(
$period = CarbonPeriod::create('2010-05-06', '2010-05-25', CarbonPeriod::EXCLUDE_START_DATE);

$exclude = $period->getOptions() & CarbonPeriod::EXCLUDE_START_DATE;
)}}

{{::exec(echo $period->getStartDate();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $period->getEndDate();/*pad(40)*/)}} // {{eval}}
// Note than ->getEndDate() will return null when the end is not fixed.
// For example CarbonPeriod::since('2018-04-21')->times(3) use repetition, so we don't know the end before iteration.
// Then you can use ->calculateEnd() instead that will use getEndDate() if available and else will execute a complete
// iteration to calculate the end date.
echo "\n";
{{::exec(echo $period->getDateInterval();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $exclude ? 'exclude' : 'include';/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::exec(var_dump($period->isStartIncluded());/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(var_dump($period->isEndIncluded());/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(var_dump($period->isStartExcluded());/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(var_dump($period->isEndExcluded());/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::exec(echo $period->getIncludedStartDate();/*pad(40)*/)}} // {{eval}}
// If start is included getIncludedStartDate() = getStartDate()
// If start is excluded getIncludedStartDate() = getStartDate() + 1 interval
echo "\n";
{{::exec(echo $period->getIncludedEndDate();/*pad(40)*/)}} // {{eval}}
// If end is included getIncludedEndDate() = getEndDate()
// If end is excluded getIncludedEndDate() = getEndDate() - 1 interval
// If end is null getIncludedEndDate() = calculateEnd(), it means the period is actually iterated to get the last date
echo "\n";

{{::exec(echo $period->toString();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $period; /*implicit toString*//*pad(40)*/)}} // {{eval}}

```

Additional getters let you access the results as an array:

```php
{{::lint(
$period = CarbonPeriod::create('2010-05-11', '2010-05-13');
)}}

{{::exec(echo $period->count();/*pad(40)*/)}} // {{eval}}, equivalent to count($period)
echo "\n";
{{::exec(echo implode(', ', $period->toArray());/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $period->first();/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $period->last();/*pad(40)*/)}} // {{eval}}

```

Note that if you intend to work using the above functions it's a good idea to store the result of `toArray()` call to a variable and use it instead, because each call performs a full iteration internally.

To change the parameters you can use setter methods:

```php
{{::lint(
$period = CarbonPeriod::create('2010-05-01', '2010-05-14', CarbonPeriod::EXCLUDE_END_DATE);
)}}

{{::lint($period->setStartDate('2010-05-11');)}}
{{::exec(echo implode(', ', $period->toArray());/*pad(40)*/)}} // {{eval}}
echo "\n";

// Second argument can be optionally used to exclude the date from the results.
{{::lint($period->setStartDate('2010-05-11', false);)}}
{{::lint($period->setEndDate('2010-05-14', true);)}}
{{::exec(echo implode(', ', $period->toArray());/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($period->setRecurrences(2);)}}
{{::exec(echo implode(', ', $period->toArray());/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($period->setDateInterval('PT12H');)}}
{{::exec(echo implode(', ', $period->toArray());/*pad(40)*/)}} // {{eval}}

// This can also be set to 12 hours in all the following ways:
{{::lint($period->setDateInterval('12h');)}}
{{::lint($period->setDateInterval('12 hours');)}}
{{::lint($period->setDateInterval(12, 'hours');)}}
{{::lint($period->setDateInterval(12, \Carbon\Unit::Hour);)}}

// And reset to no explicit interval (will then use 1 day if iterated)
{{::lint($period->resetDateInterval();)}}

```

You can change options using `setOptions()` to replace all options but you also can change them separately:

```php
{{::lint(
$period = CarbonPeriod::create('2010-05-06', '2010-05-25');
)}}

{{::exec(var_dump($period->isStartExcluded());/*pad(40)*/)}} // {{eval}}
{{::exec(var_dump($period->isEndExcluded());/*pad(40)*/)}} // {{eval}}

{{::lint($period->toggleOptions(CarbonPeriod::EXCLUDE_START_DATE, true);)}} // true, false or nothing to invert the option
{{::exec(var_dump($period->isStartExcluded());/*pad(40)*/)}} // {{eval}}
{{::exec(var_dump($period->isEndExcluded());/*pad(40)*/)}} // {{eval}} (unchanged)

{{::lint($period->excludeEndDate();/*pad(40)*/)}} // specify false to include, true or omit to exclude
{{::exec(var_dump($period->isStartExcluded());/*pad(40)*/)}} // {{eval}} (unchanged)
{{::exec(var_dump($period->isEndExcluded());/*pad(40)*/)}} // {{eval}}

{{::lint($period->excludeStartDate(false);/*pad(40)*/)}} // specify false to include, true or omit to exclude
{{::exec(var_dump($period->isStartExcluded());/*pad(40)*/)}} // {{eval}}
{{::exec(var_dump($period->isEndExcluded());/*pad(40)*/)}} // {{eval}}

```

You can check 2 periods overlap or not:

```php
{{::lint(
$period = CarbonPeriod::create('2010-05-06', '2010-05-25');
$period2 = CarbonPeriod::create('2010-05-22', '2010-05-24');
)}}

{{::exec(var_dump($period->overlaps('2010-05-22', '2010-06-03'));/*pad(58)*/)}} // {{eval}}
{{::exec(var_dump($period->overlaps($period2));/*pad(58)*/)}} // {{eval}}

{{::lint(
$period = CarbonPeriod::create('2010-05-06 12:00', '2010-05-25');
$start = Carbon::create('2010-05-06 05:00');
$end = Carbon::create('2010-05-06 11:59');
)}}
{{::exec(var_dump($period->overlaps($start, $end));/*pad(58)*/)}} // {{eval}}

```

As mentioned earlier, per ISO 8601 specification, recurrences is a number of times the interval should be repeated. The native DatePeriod will thus vary the number of returned dates depending on the exclusion of the start date. Meanwhile, CarbonPeriod being more forgiving in terms of input and allowing custom filters, treats recurrences as an overall limit for number of returned dates:

```php
{{::lint(
$period = CarbonPeriod::createFromIso('R4/2012-07-01T00:00:00Z/P7D');
$days = [];
foreach ($period as $date) {
    $days[] = $date->format('d');
}
)}}

{{::exec(echo $period->getRecurrences();/*pad(40)*/)}} // {{eval}}
{{::exec(echo implode(', ', $days);/*pad(40)*/)}} // {{eval}}

{{::lint(
$days = [];
$period->setRecurrences(3)->excludeStartDate();
foreach ($period as $date) {
    $days[] = $date->format('d');
}
)}}

{{::exec(echo $period->getRecurrences();/*pad(40)*/)}} // {{eval}}
{{::exec(echo implode(', ', $days);/*pad(40)*/)}} // {{eval}}

{{::lint(
$days = [];
$period = CarbonPeriod::recurrences(3)->sinceNow();
foreach ($period as $date) {
    $days[] = $date->format('Y-m-d');
}
)}}

{{::exec(echo implode(', ', $days);/*pad(40)*/)}} // {{eval}}

```

Dates returned by the DatePeriod can be easily filtered. Filters can be used for example to skip certain dates or iterate only over working days or weekends. A filter function should return `true` to accept a date, `false` to skip it but continue searching or `CarbonPeriod::END_ITERATION` to end the iteration.

```php
{{::lint(
$period = CarbonPeriod::between('2000-01-01', '2000-01-15');
$weekendFilter = function ($date) {
    return $date->isWeekend();
};
$period->filter($weekendFilter);)}}

{{::lint(
$days = [];
foreach ($period as $date) {
    $days[] = $date->format('m-d');
}
)}}
{{::exec(echo implode(', ', $days);/*pad(50)*/)}} // {{eval}}

```

You also can skip one or more value(s) inside the loop.

```php
{{::lint(
$period = CarbonPeriod::between('2000-01-01', '2000-01-10');
$days = [];
foreach ($period as $date) {
    $day = $date->format('m-d');
    $days[] = $day;
    if ($day === '01-04') {
        $period->skip(3);
    }
}
)}}
{{::exec(echo implode(', ', $days);/*pad(50)*/)}} // {{eval}}

```

`getFilters()` allow you to retrieve all the stored filters in a period. But be aware the recurrences limit and the end date will appear in the returned array as they are stored internally as filters.

```php
{{::lint(
$period = CarbonPeriod::end('2000-01-01')->recurrences(3);
)}}
{{::exec(var_export($period->getFilters());)}}
/*
{{eval}}
*/

```

Filters are stored in a stack and can be managed using a special set of methods:

```php
{{::lint(
$period = CarbonPeriod::between('2000-01-01', '2000-01-15');
$weekendFilter = function ($date) {
    return $date->isWeekend();
};
)}}

{{::exec(var_dump($period->hasFilter($weekendFilter));/*pad(50)*/)}} // {{eval}}
{{::lint($period->addFilter($weekendFilter);)}}
{{::exec(var_dump($period->hasFilter($weekendFilter));/*pad(50)*/)}} // {{eval}}
{{::lint($period->removeFilter($weekendFilter);)}}
{{::exec(var_dump($period->hasFilter($weekendFilter));/*pad(50)*/)}} // {{eval}}

// To avoid storing filters as variables you can name your filters:
{{::lint($period->prependFilter(function ($date) {
    return $date->isWeekend();
}, 'weekend');)}}

{{::exec(var_dump($period->hasFilter('weekend'));/*pad(50)*/)}} // {{eval}}
{{::lint($period->removeFilter('weekend');)}}
{{::exec(var_dump($period->hasFilter('weekend'));/*pad(50)*/)}} // {{eval}}

```

Order in which filters are added can have an impact on the performance and on the result, so you can use `addFilter()` to add a filter in the end of stack; and you can use `prependFilter()` to add one at the beginning. You can even use `setFilters()` to replace all filters. Note that you'll have to keep correct format of the stack and remember about internal filters for recurrences limit and end date. Alternatively you can use `resetFilters()` method and then add new filters one by one.

For example, when you add a custom filter that limits the number of attempted dates, the result will be different if you add it before or after the weekday filter.

```php
{{::lint(
// Note that you can pass a name of any Carbon method starting with "is", including macros
$period = CarbonPeriod::between('2018-05-03', '2018-05-25')->filter('isWeekday');
)}}

{{::lint(
$attempts = 0;
$attemptsFilter = function () use (&$attempts) {
    return ++$attempts <= 5 ? true : CarbonPeriod::END_ITERATION;
};

$period->prependFilter($attemptsFilter, 'attempts');
$days = [];
foreach ($period as $date) {
    $days[] = $date->format('m-d');
}
)}}
{{::exec(echo implode(', ', $days);/*pad(50)*/)}} // {{eval}}

{{::lint(
$attempts = 0;

$period->removeFilter($attemptsFilter)->addFilter($attemptsFilter, 'attempts');
$days = [];
foreach ($period as $date) {
    $days[] = $date->format('m-d');
}
)}}
{{::exec(echo implode(', ', $days);/*pad(50)*/)}} // {{eval}}

```

Note that the built-in recurrences filter doesn't work this way. It is instead based on the current key which is incremented only once per item, no matter how many dates have to be checked before a valid date is found. This trick makes it work the same both if you put it at the beginning or at the end of the stack.

A number of aliases has been added to simplify building the CarbonPeriod:

```php
// "start", "since", "sinceNow":
CarbonPeriod::start('2017-03-10') == CarbonPeriod::create()->setStartDate('2017-03-10');
// Same with optional boolean argument $inclusive to change the option about include/exclude start date:
CarbonPeriod::start('2017-03-10', true) == CarbonPeriod::create()->setStartDate('2017-03-10', true);
// "end", "until", "untilNow":
CarbonPeriod::end('2017-03-20') == CarbonPeriod::create()->setEndDate('2017-03-20');
// Same with optional boolean argument $inclusive to change the option about include/exclude end date:
CarbonPeriod::end('2017-03-20', true) == CarbonPeriod::create()->setEndDate('2017-03-20', true);
// "dates", "between":
CarbonPeriod::dates(..., ...) == CarbonPeriod::create()->setDates(..., ...);
// "recurrences", "times":
CarbonPeriod::recurrences(5) == CarbonPeriod::create()->setRecurrences(5);
// "options":
CarbonPeriod::options(...) == CarbonPeriod::create()->setOptions(...);
// "toggle":
CarbonPeriod::toggle(..., true) == CarbonPeriod::create()->toggleOptions(..., true);
// "filter", "push":
CarbonPeriod::filter(...) == CarbonPeriod::create()->addFilter(...);
// "prepend":
CarbonPeriod::prepend(...) == CarbonPeriod::create()->prependFilter(...);
// "filters":
CarbonPeriod::filters(...) == CarbonPeriod::create()->setFilters(...);
// "interval", "each", "every", "step", "stepBy":
CarbonPeriod::interval(...) == CarbonPeriod::create()->setDateInterval(...);
// "invert":
CarbonPeriod::invert() == CarbonPeriod::create()->invertDateInterval();
// "year", "months", "month", "weeks", "week", "days", "dayz", "day",
// "hours", "hour", "minutes", "minute", "seconds", "second":
CarbonPeriod::hours(5) == CarbonPeriod::create()->setDateInterval(CarbonInterval::hours(5));

```

CarbonPeriod can be easily converted to a human-readable string and ISO 8601 specification:

```php
{{::lint(
$period = CarbonPeriod::create('2000-01-01 12:00', '3 days 12 hours', '2000-01-15 12:00');
)}}
{{::exec(echo $period->toString();/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $period->toIso8601String();/*pad(36)*/)}} // {{eval}}

```

Period use and return Carbon instance by default, but you can easily set/get the date class to use in order to get immutable dates for example or any class implementing CarbonInterface.

```php
{{::lint(
$period = new CarbonPeriod;
$period->setDateClass(CarbonImmutable::class);
$period->every('3 days 12 hours')->since('2000-01-01 12:00')->until('2000-01-15 12:00');
)}}

{{::exec(echo $period->getDateClass();/*pad(42)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $period->getStartDate();/*pad(42)*/)}} // {{eval}}
echo "\n";
{{::exec(echo get_class($period->getStartDate());/*pad(42)*/)}} // {{eval}}

```

CarbonPeriod has `forEach()` and `map()` helper methods:

```php
{{::lint(
$period = CarbonPeriod::create('2018-04-21', '3 days', '2018-04-27');
)}}
{{::exec(
$dates = $period->map(function (Carbon $date) {
    return $date->format('m-d');
});
// Or with PHP 7.4:
// $dates = $period->map(fn(Carbon $date) => $date->format('m-d'));
$array = iterator_to_array($dates); // $dates is a iterable \Generator
var_dump($array);
echo implode(', ', $array);
)}}
/*
{{eval}}
*/
echo "\n";

// Here is what happens under the hood:
{{::exec(
$period->forEach(function (Carbon $date) {
    echo $date->format('m-d')."\n";
});)}}
/*
{{eval}}*/

```

As all other Carbon classes, `CarbonPeriod` has a `cast()` method to convert it:

```php
{{::lint(
$period = CarbonPeriod::create('2000-01-01 12:00', '3 days 12 hours', '2000-01-15 12:00');

// It would also works if your class extends DatePeriod
class MyPeriod extends CarbonPeriod {}
)}}

{{::exec(echo get_class($period->cast(MyPeriod::class));/*pad(42)*/)}} // {{eval}}

// Shortcut to export as raw DatePeriod:
{{::exec(echo get_class($period->toDatePeriod());/*pad(42)*/)}} // {{eval}}

```

You can check if periods follow themselves. Period **A** follows period **B** if the first iteration date of **B** equals to the last iteration date of **A** + the interval of **A**. For example `[2019-02-01 => 2019-02-16]` follows `[2019-01-15 => 2019-01-31]` (assuming neither start nor end are excluded via option for those period and assuming those period as a (1 day)-interval.

```php
{{::lint(
$a = CarbonPeriod::create('2019-01-15', '2019-01-31');
$b = CarbonPeriod::create('2019-02-01', '2019-02-16');
)}}

{{::exec(var_dump($b->follows($a));/*pad(40)*/)}} // {{eval}}
{{::exec(var_dump($a->isFollowedBy($b));/*pad(40)*/)}} // {{eval}}
// ->isConsecutiveWith($period) is true if it either ->follows($period) or ->isFollowedBy($period)
{{::exec(var_dump($b->isConsecutiveWith($a));/*pad(40)*/)}} // {{eval}}
{{::exec(var_dump($a->isConsecutiveWith($b));/*pad(40)*/)}} // {{eval}}

```

The `contains()` method allow you to check if a date is in the period range.

```php
{{::lint(
$period = CarbonPeriod::create('2019-01-15', '2019-01-31');
)}}

{{::exec(var_dump($period->contains('2019-01-22'));/*pad(50)*/)}} // {{eval}}

```

The comparison includes start and end unless you excluded them in the option and as for it concerns `contains()`, the exclusion only exclude the exact date, so:

```php
{{::lint(
$period = CarbonPeriod::create('2019-01-15', '2019-01-31', CarbonPeriod::EXCLUDE_END_DATE);
)}}

{{::exec(var_dump($period->contains('2019-01-31 00:00:00'));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($period->contains('2019-01-30 23:59:59'));/*pad(50)*/)}} // {{eval}}

```

You can use start/end comparisons methods (that ignore exclusions) for more precise comparisons:

*   `startsAt()` start == date
*   `startsBefore()` start < date
*   `startsBeforeOrAt()` start <= date
*   `startsAfter()` start > date
*   `startsAfterOrAt()` start >= date
*   `endsAt()` end == date
*   `endsBefore()` end < date
*   `endsBeforeOrAt()` end <= date
*   `endsAfter()` end > date
*   `endsAfterOrAt()` end >= date
*   `isStarted()` start <= now
*   `isEnded()` end <= now
*   `isInProgress()` started but not ended
