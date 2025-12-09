---
order: 4
---

# Testing Aids

The testing methods allow you to set a Carbon instance (real or mock) to be returned when a "now" instance is created. The provided instance will be used when retrieving any relative time from Carbon (now, today, yesterday, next month, etc.)

```php
{{::lint($knownDate = Carbon::create(2001, 5, 21, 12);/*pad(54)*/)}} // create testing date
{{::lint(Carbon::setTestNow($knownDate);/*pad(54)*/)}} // set the mock (of course this could be a real mock object)
{{::exec(echo Carbon::getTestNow();/*pad(54)*/)}} // {{eval}}
{{::exec(echo Carbon::now();/*pad(54)*/)}} // {{eval}}
{{::exec(echo new Carbon();/*pad(54)*/)}} // {{eval}}
{{::exec(echo new Carbon('now');/*pad(54)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('now');/*pad(54)*/)}} // {{eval}}
{{::exec(echo Carbon::create(2001, 4, 21, 12)->diffForHumans();/*pad(54)*/)}} // {{eval}}

// This will trigger an actual sleep(3) in prod, but when time is mocked,
// This will set the test-now to 3 seconds later:
{{::lint(Carbon::sleep(3);)}}
{{::exec(echo Carbon::now();/*pad(54)*/)}} // {{eval}}

{{::exec(var_dump(Carbon::hasTestNow());/*pad(54)*/)}} // {{eval}}
{{::lint(Carbon::setTestNow();/*pad(54)*/)}} // clear the mock
{{::exec(var_dump(Carbon::hasTestNow());/*pad(54)*/)}} // {{eval}}
{{::exec(echo Carbon::now();/*pad(54)*/)}} // {{eval}}
// Instead of mock and clear mock, you also can use withTestNow():

{{::exec(Carbon::withTestNow('2010-09-15', static function () {
    echo Carbon::now();
});/*pad(54)*/)}} // {{eval}}

```

A more meaning full example:

```php
{{::lint(
class SeasonalProduct
{
    protected $price;

    public function __construct($price)
    {
        $this->price = $price;
    }

    public function getPrice() {
        $multiplier = 1;
        if (Carbon::now()->month == 12) {
            $multiplier = 2;
        }

        return $this->price * $multiplier;
    }
}

$product = new SeasonalProduct(100);
)}}
{{::lint(Carbon::setTestNow(Carbon::parse('first day of March 2000'));/*pad(40)*/)}}
{{::exec(echo $product->getPrice();/*pad(70)*/)}} // {{eval}}
{{::lint(Carbon::setTestNow(Carbon::parse('first day of December 2000'));/*pad(40)*/)}}
{{::exec(echo $product->getPrice();/*pad(70)*/)}} // {{eval}}
{{::lint(Carbon::setTestNow(Carbon::parse('first day of May 2000'));/*pad(40)*/)}}
{{::exec(echo $product->getPrice();/*pad(70)*/)}} // {{eval}}
{{::lint(Carbon::setTestNow();)}}

```

Relative phrases are also mocked according to the given "now" instance.

```php
{{::lint($knownDate = Carbon::create(2001, 5, 21, 12);/*pad(54)*/)}} // create testing date
{{::lint(Carbon::setTestNow($knownDate);/*pad(54)*/)}} // set the mock
{{::exec(echo new Carbon('tomorrow');/*pad(54)*/)}} // {{eval}}  ... notice the time !
{{::exec(echo new Carbon('yesterday');/*pad(54)*/)}} // {{eval}}
{{::exec(echo new Carbon('next wednesday');/*pad(54)*/)}} // {{eval}}
{{::exec(echo new Carbon('last friday');/*pad(54)*/)}} // {{eval}}
{{::exec(echo new Carbon('this thursday');/*pad(54)*/)}} // {{eval}}
{{::exec(Carbon::setTestNow();/*pad(54)*/)}} // always clear it !

```

Since Carbon 2.56.0, `setTestNow()` no longer impact the timezone of the `Carbon::now()` instance you'll get. This was done because in real life, `Carbon::now()` returns a date with the timezone from `date_default_timezone_get()`. And tests should reflect this.

You can use `setTestNowAndTimezone()` to mock the time and change the default timezone using `date_default_timezone_set()`:

```php
{{::lint(Carbon::setTestNowAndTimezone(Carbon::parse('2022-01-24 10:45 America/Toronto'));)}}
// or
{{::lint(Carbon::setTestNowAndTimezone('2022-01-24 10:45', 'America/Toronto');)}}
{{::exec(echo Carbon::now()->format('Y-m-d e');)}} // {{eval}}
{{::exec(Carbon::setTestNow();)}} // clear time mock
{{::exec(date_default_timezone_set('UTC');)}} // restore default timezone

```

The list of words that are considered to be relative modifiers are:

*   +
*   \-
*   ago
*   first
*   next
*   last
*   this
*   today
*   tomorrow
*   yesterday

Be aware that similar to the next(), previous() and modify() methods some of these relative modifiers will set the time to 00:00:00.

`Carbon::parse($time, $tz)` and `new Carbon($time, $tz)` both can take a timezone as second argument.

```php
{{::exec(echo Carbon::parse('2012-9-5 23:26:11.223', 'Europe/Paris')->timezone->getName();)}} // {{eval}}

```

[See Carbonite for more advanced Carbon testing features.](https://github.com/kylekatarnls/carbonite)

Carbonite is an additional package you can easily install using composer: `composer require --dev kylekatarnls/carbonite` then use to travel times in your unit tests as you would tell a story:

Add `use Carbon\Carbonite;` import at the top of the file.

```php
{{::lint($holidays = CarbonPeriod::create('2019-12-23', '2020-01-06', CarbonPeriod::EXCLUDE_END_DATE);

Carbonite::freeze('2019-12-22'); // Freeze the time to a given date)}}

{{::exec(var_dump($holidays->isStarted());/*pad(37)*/)}} // {{eval}}

// Then go to anytime:
{{::lint(Carbonite::elapse('1 day');)}}

{{::exec(var_dump($holidays->isInProgress());/*pad(37)*/)}} // {{eval}}

{{::lint(Carbonite::jumpTo('2020-01-05 22:00');)}}

{{::exec(var_dump($holidays->isEnded());/*pad(37)*/)}} // {{eval}}

{{::lint(Carbonite::elapse('2 hours');)}}

{{::exec(var_dump($holidays->isEnded());/*pad(37)*/)}} // {{eval}}

{{::lint(Carbonite::rewind('1 microsecond');)}}

{{::exec(var_dump($holidays->isEnded());/*pad(37)*/)}} // {{eval}}

{{::lint(Carbonite::release(); // Release time after each test)}}

```
