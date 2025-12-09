# Introduction

The Carbon class is [inherited](https://www.php.net/manual/en/language.oop5.inheritance.php) from the PHP [DateTime](https://www.php.net/manual/en/class.datetime.php) class.

```php
<?php
namespace Carbon;

class Carbon extends \DateTime
{
    // code here
}

```

You can see from the code snippet above that the Carbon class is declared in the Carbon namespace. You need to import the namespace to use Carbon without having to provide its fully qualified name each time.

```php
use Carbon\Carbon;
```

Examples in this documentation will assume you imported classes of the Carbon namespace this way.

If you're using Laravel, you may check [our Laravel configuration and best-practices recommendations](/laravel).

If you're using Symfony, you may check [our Symfony configuration and best-practices recommendations](/symfony).

We also provide CarbonImmutable class extending [DateTimeImmutable](https://www.php.net/manual/en/class.datetimeimmutable.php). The same methods are available on both classes but when you use a modifier on a Carbon instance, it modifies and returns the same instance, when you use it on CarbonImmutable, it returns a new instances with the new value.

```php
{{::lint($mutable = Carbon::now();)}}
{{::lint($immutable = CarbonImmutable::now();)}}
{{::lint($modifiedMutable = $mutable->add(1, 'day');)}}
{{::lint($modifiedImmutable = CarbonImmutable::now()->add(1, 'day');)}}

{{::exec(var_dump($modifiedMutable === $mutable);/*pad(52)*/)}} // {{eval}}
{{::exec(var_dump($mutable->isoFormat('dddd D'));/*pad(52)*/)}} // {{eval}}
{{::exec(var_dump($modifiedMutable->isoFormat('dddd D'));/*pad(52)*/)}} // {{eval}}
// So it means $mutable and $modifiedMutable are the same object
// both set to now + 1 day.
{{::exec(var_dump($modifiedImmutable === $immutable);/*pad(52)*/)}} // {{eval}}
{{::exec(var_dump($immutable->isoFormat('dddd D'));/*pad(52)*/)}} // {{eval}}
{{::exec(var_dump($modifiedImmutable->isoFormat('dddd D'));/*pad(52)*/)}} // {{eval}}
// While $immutable is still set to now and cannot be changed and
// $modifiedImmutable is a new instance created from $immutable
// set to now + 1 day.

{{::lint($mutable = CarbonImmutable::now()->toMutable();)}}
{{::exec(var_dump($mutable->isMutable());/*pad(52)*/)}} // {{eval}}
{{::exec(var_dump($mutable->isImmutable());/*pad(52)*/)}} // {{eval}}
{{::lint($immutable = Carbon::now()->toImmutable();)}}
{{::exec(var_dump($immutable->isMutable());/*pad(52)*/)}} // {{eval}}
{{::exec(var_dump($immutable->isImmutable());/*pad(52)*/)}} // {{eval}}

```

The library also provides CarbonInterface interface extends [DateTimeInterface](https://www.php.net/manual/en/class.datetimeinterface.php) and [JsonSerializable](https://www.php.net/manual/en/class.jsonserializable.php), [CarbonInterval](#api-interval) class extends [DateInterval](https://www.php.net/manual/en/class.dateinterval.php), [CarbonTimeZone](#api-timezone) class extends [DateTimeZone](https://www.php.net/manual/en/class.datetimezone.php) and [CarbonPeriod](#api-period) class polyfills [DatePeriod](https://www.php.net/manual/en/class.dateperiod.php).

Carbon has all the functions inherited from the base DateTime class. This approach allows you to access the base functionality such as [modify](https://www.php.net/manual/en/datetime.modify.php), [format](https://www.php.net/manual/en/datetime.format.php) or [getTimestamp](https://www.php.net/manual/en/datetime.gettimestamp.php).

Now, let's see how cool this documentation page is. Click on the code below:

```php
{{::lint($dtToronto = Carbon::create(2012, 1, 1, 0, 0, 0, 'America/Toronto');)}}
{{::lint($dtVancouver = Carbon::create(2012, 1, 1, 0, 0, 0, 'America/Vancouver');)}}
// Try to replace the 4th number (hours) or the last argument (timezone) with
// Europe/Paris for example and see the actual result on the right hand.
// It's alive!

{{::exec(echo $dtVancouver->diffInHours($dtToronto);)}} // {{eval}}
// Now, try to double-click on "diffInHours" or "create" to open
// the References panel.
// Once the references panel is open, you can use the search field to
// filter the list or click the (<) button to close it.

```

Some examples are static snippets, some other are editable (when there is a top right hand corner expand button). You can also click on this button to open the snippet in a new tab. You can double-click on method names in both static and dynamic examples.

# Instantiation

There are several different methods available to create a new instance of Carbon. First there is a constructor. It overrides the [parent constructor](https://www.php.net/manual/en/datetime.construct.php) and you are best to read about the first parameter from the PHP manual and understand the date/time string formats it accepts. You'll hopefully find yourself rarely using the constructor but rather relying on the explicit static methods for improved readability.

```php
{{::lint($carbon = new Carbon();/*pad(40)*/)}} // equivalent to Carbon::now()
{{::lint($carbon = new Carbon('first day of January 2008', 'America/Vancouver');)}}
{{::exec(echo get_class($carbon);/*pad(40)*/)}} // '{{eval}}'

{{::lint($carbon = new Carbon(new \DateTime('first day of January 2008'), new \DateTimeZone('America/Vancouver'));)}} // equivalent to previous instance
// You can create Carbon or CarbonImmutable instance from:
//   - string representation
//   - integer timestamp
//   - DateTimeInterface instance (that includes DateTime, DateTimeImmutable or an other Carbon instance)
// All those are available right in the constructor, other creator methods can be found
// in the "Reference" panel searching for "create".

```

You'll notice above that the timezone (2nd) parameter was passed as a string rather than a `\DateTimeZone` instance. All DateTimeZone parameters have been augmented so you can pass a DateTimeZone instance, string or integer offset to GMT and the timezone will be created for you. This is shown again in the next example which also introduces the `now()` function.

```php
{{::lint(
$now = Carbon::now(); // will use timezone as set with date_default_timezone_set
// PS: we recommend you to work with UTC as default timezone and only use
// other timezones (such as the user timezone) on display

$nowInLondonTz = Carbon::now(new \DateTimeZone('Europe/London'));

// or just pass the timezone as a string
$nowInLondonTz = Carbon::now('Europe/London');
)}}
{{::exec(echo $nowInLondonTz->tzName;/*pad(40)*/)}} // {{eval}}
{{::lint(echo "\n";

// or to create a date with a custom fixed timezone offset
$date = Carbon::now('+13:30');
)}}
{{::exec(echo $date->tzName;/*pad(40)*/)}} // {{eval}}
{{::lint(echo "\n";

// Get/set minutes offset from UTC
)}}
{{::exec(echo $date->utcOffset();/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($date->utcOffset(180);)}}

{{::exec(echo $date->tzName;/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->utcOffset();/*pad(40)*/)}} // {{eval}}

```

If you really love your fluid method calls and get frustrated by the extra line or ugly pair of brackets necessary when using the constructor you'll enjoy the `parse` method.

```php
{{::exec(echo (new Carbon('first day of December 2008'))->addWeeks(2);/*pad(65)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::parse('first day of December 2008')->addWeeks(2);/*pad(65)*/)}} // {{eval}}

```

The string passed to `Carbon::parse` or to `new Carbon` can represent a relative time (next sunday, tomorrow, first day of next month, last year) or an absolute time (first day of December 2008, 2017-01-06). You can test if a string will produce a relative or absolute date with `Carbon::hasRelativeKeywords()`.

```php
$string = 'first day of next month';
if (strtotime($string) === false) {
    echo "'$string' is not a valid date/time string.";
} elseif (Carbon::hasRelativeKeywords($string)) {
    echo "'$string' is a relative valid date/time string, it will returns different dates depending on the current date.";
} else {
    echo "'$string' is an absolute date/time string, it will always returns the same date.";
}
```

To accompany `now()`, a few other static instantiation helpers exist to create widely known instances. The only thing to really notice here is that `today()`, `tomorrow()` and `yesterday()`, besides behaving as expected, all accept a timezone parameter and each has their time value set to `00:00:00`.

```php
{{::lint($now = Carbon::now();)}}
{{::exec(echo $now;/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::lint($today = Carbon::today();)}}
{{::exec(echo $today;/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::lint($tomorrow = Carbon::tomorrow('Europe/London');)}}
{{::exec(echo $tomorrow;/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::lint($yesterday = Carbon::yesterday();)}}
{{::exec(echo $yesterday;/*pad(40)*/)}} // {{eval}}

```

The next group of static helpers are the `createXXX()` helpers. Most of the static `create` functions allow you to provide as many or as few arguments as you want and will provide default values for all others. Generally default values are the current date, time or timezone. Higher values will wrap appropriately but invalid values will throw an `InvalidArgumentException` with an informative message. The message is obtained from an [DateTime::getLastErrors()](https://php.net/manual/en/datetime.getlasterrors.php) call.

```php
{{::lint(
$year = 2000; $month = 4; $day = 19;
$hour = 20; $minute = 30; $second = 15; $tz = 'Europe/Madrid';
echo Carbon::createFromDate($year, $month, $day, $tz)."\n";
echo Carbon::createMidnightDate($year, $month, $day, $tz)."\n";
echo Carbon::createFromTime($hour, $minute, $second, $tz)."\n";
echo Carbon::createFromTimeString("$hour:$minute:$second", $tz)."\n";
echo Carbon::create($year, $month, $day, $hour, $minute, $second, $tz)."\n";
)}}

```

`createFromDate()` will default the time to now. `createFromTime()` will default the date to today. `create()` will default any null parameter to the current respective value. As before, the `$tz` defaults to the current timezone and otherwise can be a DateTimeZone instance or simply a string timezone value. The only special case is for `create()` that has minimum value as default for missing argument but default on current value when you pass explicitly `null`.

```php
{{::lint(
$xmasThisYear = Carbon::createFromDate(null, 12, 25);  // Year defaults to current year
$Y2K = Carbon::create(2000, 1, 1, 0, 0, 0); // equivalent to Carbon::createMidnightDate(2000, 1, 1)
$alsoY2K = Carbon::create(1999, 12, 31, 24);
$noonLondonTz = Carbon::createFromTime(12, 0, 0, 'Europe/London');
$teaTime = Carbon::createFromTimeString('17:00:00', 'Europe/London');
)}}

{{::exec(try { Carbon::create(1975, 5, 21, 22, -2, 0); } catch(\InvalidArgumentException $x) { echo $x->getMessage(); })}}
// {{eval}}

// Be careful, as Carbon::createFromDate() default values to current date, it can trigger overflow:
// For example, if we are the 15th of June 2020, the following will set the date on 15:
Carbon::createFromDate(2019, 4); // 2019-04-15
// If we are the 31th of October, as 31th April does not exist, it overflows to May:
Carbon::createFromDate(2019, 4); // 2019-05-01
// That's why you simply should not use Carbon::createFromDate() with only 2 parameters (1 or 3 are safe, but no 2)

```

Create exceptions occurs on such negative values but not on overflow, to get exceptions on overflow, use `createSafe()`

```php
{{::exec(echo Carbon::create(2000, 1, 35, 13, 0, 0);)}}
// {{eval}}
echo "\n";

{{::exec(try {
    Carbon::createSafe(2000, 1, 35, 13, 0, 0);
} catch (\Carbon\Exceptions\InvalidDateException $exp) {
    echo $exp->getMessage();
})}}
// {{eval}}
```

Note 1: 2018-02-29 also throws an exception while 2020-02-29 does not since 2020 is a leap year.

Note 2: `Carbon::createSafe(2014, 3, 30, 1, 30, 0, 'Europe/London')` also produces an exception as this time is in an hour skipped by the daylight saving time.

Note 3: The PHP native API allow to consider there is a year `0` between `-1` and `1` even if it doesn't regarding Gregorian calendar. That's why years lower than 1 will throw an exception using `createSafe`. Check [isValid()](#doc-method-Carbon-isValid) for year-0 detection.

```php
Carbon::createFromFormat($format, $time, $tz);
```

`createFromFormat()` is mostly a wrapper for the base php function [DateTime::createFromFormat](https://php.net/manual/en/datetime.createfromformat.php). The difference being again the `$tz` argument can be a DateTimeZone instance or a string timezone value. Also, if there are errors with the format this function will call the `DateTime::getLastErrors()` method and then throw a `InvalidArgumentException` with the errors as the message.

```php
{{::exec(echo Carbon::createFromFormat('Y-m-d H', '1975-05-21 22')->toDateTimeString();)}} // {{eval}}
```

You can test if a date matches a format for `createFromFormat()` (e.g. date/time components, modifiers or separators) using `Carbon::hasFormatWithModifiers()` or `Carbon::canBeCreatedFromFormat()` which also ensure data is actually enough to create an instance.

```php
{{::exec(var_dump(Carbon::hasFormatWithModifiers('21/05/1975', 'd#m#Y!'));)}} // {{eval}}
// As 21 is too high for a month number and day is expected to be formatted "05":
{{::exec(var_dump(Carbon::hasFormatWithModifiers('5/21/1975', 'd#m#Y!'));)}} // {{eval}}
// 5 is ok for N format:
{{::exec(var_dump(Carbon::hasFormatWithModifiers('5', 'N'));)}} // {{eval}}
// but not enough to create an instance:
{{::exec(var_dump(Carbon::canBeCreatedFromFormat('5', 'N'));)}} // {{eval}}
// Both hasFormatWithModifiers() and hasFormat() exist because
// hasFormat() does not interpret modifiers, it checks strictly if ->format() could have produce the given
// string with the given format:
{{::exec(var_dump(Carbon::hasFormat('21/05/1975', 'd#m#Y!'));)}} // {{eval}}
{{::exec(var_dump(Carbon::hasFormat('21#05#1975!', 'd#m#Y!'));)}} // {{eval}}
```

You can create instances from [unix timestamps](https://en.wikipedia.org/wiki/Unix_time). `createFromTimestamp()` create a Carbon instance equal to the given timestamp and will set the timezone to the given timezone as second parameter, or to UTC if non given (since Carbon 3) (in previous versions it defaulted to `date_default_timezone_get()`). It supports int, float or string containing one or more numbers (like the one produced by `microtime()`) so it can also set microseconds with no precision lost. The third, `createFromTimestampMs()`, accepts a timestamp in milliseconds instead of seconds. Negative timestamps are also allowed.

```php
{{::exec(echo Carbon::createFromTimestamp(-1)->toDateTimeString();/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestamp(-1.5, 'Europe/London')->toDateTimeString();/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestampUTC(-1)->toDateTimeString();/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestamp('1601735792.198956', 'Europe/London')->format('Y-m-d\TH:i:s.uP');/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestampUTC('0.198956 1601735792')->format('Y-m-d\TH:i:s.uP');/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestampMs(1)->format('Y-m-d\TH:i:s.uP');/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestampMs('1601735792198.956', 'Europe/London')->format('Y-m-d\TH:i:s.uP');/*pad(101)*/)}} // {{eval}}
{{::exec(echo Carbon::createFromTimestampMsUTC('0.956 1601735792198')->format('Y-m-d\TH:i:s.uP');/*pad(101)*/)}} // {{eval}}

```

You can also create a `copy()` of an existing Carbon instance. As expected the date, time and timezone values are all copied to the new instance.

```php
{{::lint($dt = Carbon::now();)}}
{{::exec(echo $dt->diffInYears($dt->copy()->addYear());)}}  // {{eval}}

// $dt was unchanged and still holds the value of Carbon:now()

// Without ->copy() it would return 0 because addYear() modify $dt so
// diffInYears() compare $dt with itself:
{{::exec(echo $dt->diffInYears($dt->addYear());)}}  // {{eval}}

// Note that this would not happen neither with CarbonImmutable
// When immutable, any add/sub methods return a new instance:
{{::lint($dt = CarbonImmutable::now();)}}
{{::exec(echo $dt->diffInYears($dt->addYear());)}}  // {{eval}}

// Last, when your variable can be either a Carbon or CarbonImmutable,
// You can use avoidMutation() which will copy() only if the given date
// is mutable:
{{::exec(echo $dt->diffInYears($dt->avoidMutation()->addYear());)}}  // {{eval}}

```

You can use `nowWithSameTz()` on an existing Carbon instance to get a new instance at now in the same timezone.

```php
{{::lint($meeting = Carbon::createFromTime(19, 15, 00, 'Africa/Johannesburg');)}}

// 19:15 in Johannesburg
{{::exec(echo 'Meeting starts at '.$meeting->format('H:i').' in Johannesburg.';/*pad(86)*/)}}  // {{eval}}
// now in Johannesburg
{{::exec(echo "It's ".$meeting->nowWithSameTz()->format('H:i').' right now in Johannesburg.';/*pad(86)*/)}}  // {{eval}}

```

Finally, if you find yourself inheriting a `\DateTime` instance from another library, fear not! You can create a `Carbon` instance via a friendly `instance()` method. Or use the even more flexible method `make()` which can return a new Carbon instance from a DateTime, Carbon or from a string, else it just returns null.

```php
{{::lint($dt = new \DateTime('first day of January 2008');)}} // <== instance from another API
{{::lint($carbon = Carbon::instance($dt);)}}
{{::exec(echo get_class($carbon);/*pad(54)*/)}} // '{{eval}}'
{{::exec(echo $carbon->toDateTimeString();/*pad(54)*/)}} // {{eval}}

```

Carbon 2 (requiring PHP >= 7.1) perfectly supports microseconds. But if you use Carbon 1 and PHP < 7.1, read our [section about partial microseconds support](#partial-microseconds-support-v1).

Before PHP 7.1 DateTime microseconds are not added to `"now"` instances and cannot be changed afterward, this means:

```php
$date = new \DateTime('now');
echo $date->format('u');
// display current microtime in PHP >= 7.1 (expect a bug in PHP 7.1.3 only)
// display 000000 before PHP 7.1

$date = new \DateTime('2001-01-01T00:00:00.123456Z');
echo $date->format('u');
// display 123456 in all PHP versions

$date->modify('00:00:00.987654');
echo $date->format('u');
// display 987654 in PHP >= 7.1
// display 123456 before PHP 7.1

```

To work around this limitation in Carbon, we append microseconds when calling `now` in PHP < 7.1, but this feature can be disabled on demand (no effect in PHP >= 7.1):

```php
Carbon::useMicrosecondsFallback(false);
var_dump(Carbon::isMicrosecondsFallbackEnabled()); // false

echo Carbon::now()->micro; // 0 in PHP < 7.1, microtime in PHP >= 7.1

Carbon::useMicrosecondsFallback(true); // default value
var_dump(Carbon::isMicrosecondsFallbackEnabled()); // true

echo Carbon::now()->micro; // microtime in all PHP version

```

# Localization

With Carbon 2, localization changed a lot, {{eval(echo count(Carbon::getAvailableLocales()) - 73;)}} new locales are supported, and we now embed locale formats, day names, month names, ordinal suffixes, meridiem, week start and more. While Carbon 1 provided partial support and relied on third-party like IntlDateFormatter class and language packages for advanced translation, you now benefit of a wide internationalization support. You still use Carbon 1? I hope you would consider to upgrade, version 2 has really cool new features. Otherwise, you can find the [version 1 documentation of Localization by clicking here](#localization-v1).

You can easily customize translations:

```php
{{::lint(
// we recommend to use custom language name/variant
// rather than overriding an existing language
// to avoid conflict such as "en_Boring" in the example below:
$boringLanguage = 'en_Boring';
$translator = \Carbon\Translator::get($boringLanguage);
$translator->setTranslations([
    'day' => ':count boring day|:count boring days',
]);
// as this language starts with "en_" it will inherit from the locale "en"

$date1 = Carbon::create(2018, 1, 1, 0, 0, 0);
$date2 = Carbon::create(2018, 1, 4, 4, 0, 0);
)}}

{{::exec(echo $date1->locale($boringLanguage)->diffForHumans($date2);)}} // {{eval}}

{{::lint(
$translator->setTranslations([
    'before' => function ($time) {
        return '['.strtoupper($time).']';
    },
]);
)}}

{{::exec(echo $date1->locale($boringLanguage)->diffForHumans($date2);)}} // {{eval}}

```

You can use fallback locales by passing in order multiple ones to `locale()`:

```php
{{::lint(
\Carbon\Translator::get('xx')->setTranslations([
    'day' => ':count Xday',
]);
\Carbon\Translator::get('xy')->setTranslations([
    'day' => ':count Yday',
    'hour' => ':count Yhour',
]);

$date = Carbon::now()->locale('xx', 'xy', 'es')->sub('3 days 6 hours 40 minutes');
)}}

{{::exec(echo $date->ago(['parts' => 3]);)}} // {{eval}}

```

In the example above, it will try to find translations in "xx" in priority, then in "xy" if missing, then in "es", so here, you get "Xday" from "xx", "Yhour" from "xy", and "hace" and "minutos" from "es".

Note that you can also use an other translator with `Carbon::setTranslator($custom)` as long as the given translator implements [`Symfony\Component\Translation\TranslatorInterface`](https://symfony.com/doc/current/translation.html). And you can get the global default translator using `Carbon::getTranslator()` (and `Carbon::setFallbackLocale($custom)` and `Carbon::getFallbackLocale()` for the fallback locale, setFallbackLocale can be called multiple times to get multiple fallback locales) but as those method will change the behavior globally (including third-party libraries you may have in your app), it might cause unexpected results. You should rather customize translation using custom locales as in the example above.

Carbon embed a default translator that extends Symfony\\Component\\Translation\\Translator You can [check here the methods we added to it](#translator-details).

The Carbon translator will use internal directory `src/Carbon/Lang` to find translations files in it by default but you can change/add/remove directory.

```php
{{::lint(
$translator = Translator::get('en');
$directories = $translator->getDirectories();
var_dump($directories); // Check actual directory

// Change the whole list
$translator->setDirectories([
    'corporate/translations',
    'users/translations',
]);
// Add one directory to the list
$translator->addDirectory('external/translations/directory');
// Remove one directory from the list
$translator->removeDirectory('users/translations');

// After such a settings change, you could need to clear the cache with `resetMessages`
$translator->resetMessages();

// To restore the initial settings simply recall setDirectories with the original list:
$translator->setDirectories($directories);
)}}

```

Then you can find all language files across those directories.

```php
{{::lint(
$translator = Translator::get();
var_dump($translator->getLocalesFiles()); // /path/to/af.php, /path/to/ar.php, etc.
var_dump($translator->getAvailableLocales()); // af, ar, etc.

// You can also filter files/locales starting with a given prefix:)}}
{{::exec(echo implode(', ', array_map('basename', $translator->getLocalesFiles('fr')));)}} // {{eval}}
{{::exec(echo implode(', ', $translator->getAvailableLocales('fr'));)}} // {{eval}}

```

You can access some dynamic properties translated by calling following methods with the name of the base property.

```php
{{::lint(
$date = Carbon::parse('2018-02-25 14:00');
)}}
{{::exec(echo $date->locale('af_ZA')->meridiem();/*pad(60)*/)}} // {{eval}}
{{::lint(echo "\n";)}}
{{::exec(echo $date->locale('af_ZA')->meridiem(true);/*pad(60)*/)}} // {{eval}}
{{::lint(echo "\n";)}}
// Some languages has alternative numbers available:
{{::exec(echo $date->locale('ja_JP')->translateNumber(45);/*pad(60)*/)}} // {{eval}}
{{::lint(echo "\n";)}}
// You can also choose a key linked to a numeric value to translate:
{{::exec(echo $date->locale('ja_JP')->getAltNumber('day');/*pad(60)*/)}} // {{eval}}
// Note: translations methods like translateNumber and getAltNumber are available
// on CarbonInterval and CarbonPeriod too.
{{::lint(echo "\n";)}}
{{::exec(echo $date->locale('en_SG')->ordinal('day');/*pad(60)*/)}} // {{eval}}
{{::lint(echo "\n";)}}
// As ordinal can be gender specific or have context dependency, you can pass the period format as second argument:
{{::lint(
$date = Carbon::parse('2018-01-01 14:00');
)}}
{{::exec(echo $date->locale('fr_CH')->ordinal('isoWeek', 'w');/*pad(60)*/)}} // {{eval}}
{{::lint(echo "\n";)}}
{{::exec(echo $date->locale('fr_CH')->ordinal('day', 'd');/*pad(60)*/)}} // {{eval}}
{{::lint(echo "\n";)}}

```

Finally, you can get and set messages from the internal cache:

```php
{{::lint(
$translator = Translator::get('en');
)}}
{{::exec(echo Carbon::now()->addSeconds(312)->setLocalTranslator($translator)->diffForHumans();)}} // {{eval}}
{{::lint(echo "\n";

// Below, setMessages will load the english file(s) if available and if not yet loaded in cache, then will change the
// 'from_now' translation
$translator->setMessages('en', [
    'from_now' => 'in :time',
]);
)}}
{{::exec(echo Carbon::now()->addSeconds(312)->setLocalTranslator($translator)->diffForHumans();)}} // {{eval}}
echo "\n";
{{::exec(echo $translator->getMessages('en')['from_now'];)}} // {{eval}}

```

`setMessages` is equivalent to `setTranslations` but you can omit the locale as it will use the current one, so we recommend to use it when you can as in [this previous example](#custom-translations).

You can check what's supported with the following methods:

```php
{{::exec(echo implode(', ', array_slice(Carbon::getAvailableLocales(), 0, 3)).'...';/*pad(80)*/)}} // {{eval}}

// Support diff syntax (before, after, from now, ago)
{{::exec(var_dump(Carbon::localeHasDiffSyntax('en'));/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::localeHasDiffSyntax('zh_TW'));/*pad(80)*/)}} // {{eval}}
// Support 1-day diff words (just now, yesterday, tomorrow)
{{::exec(var_dump(Carbon::localeHasDiffOneDayWords('en'));/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::localeHasDiffOneDayWords('zh_TW'));/*pad(80)*/)}} // {{eval}}
// Support 2-days diff words (before yesterday, after tomorrow)
{{::exec(var_dump(Carbon::localeHasDiffTwoDayWords('en'));/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::localeHasDiffTwoDayWords('zh_TW'));/*pad(80)*/)}} // {{eval}}
// Support short units (1y = 1 year, 1mo = 1 month, etc.)
{{::exec(var_dump(Carbon::localeHasShortUnits('en'));/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::localeHasShortUnits('zh_TW'));/*pad(80)*/)}} // {{eval}}
// Support period syntax (X times, every X, from X, to X)
{{::exec(var_dump(Carbon::localeHasPeriodSyntax('en'));/*pad(80)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::localeHasPeriodSyntax('zh_TW'));/*pad(80)*/)}} // {{eval}}

```

So, here is the new recommended way to handle internationalization with Carbon.

```php
{{::lint($date = Carbon::now()->locale('fr_FR');)}}

{{::exec(echo $date->locale();/*pad(32)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->diffForHumans();/*pad(32)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->monthName;/*pad(32)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('LLLL');/*pad(32)*/)}} // {{eval}}

```

The `->locale()` method only change the language for the current instance and has precedence over global settings. We recommend you this approach so you can't have conflict with other places or third-party libraries that could use Carbon. Nevertheless, to avoid calling `->locale()` each time, you can use factories.

```php
{{::lint(// Let say Martin from Paris and John from Chicago play chess
$martinDateFactory = new Factory([
    'locale' => 'fr_FR',
    'timezone' => 'Europe/Paris',
]);
$johnDateFactory = new Factory([
    'locale' => 'en_US',
    'timezone' => 'America/Chicago',
]);
// Each one will see date in his own language and timezone

// When Martin moves, we display things in French, but we notify John in English:
$gameStart = Carbon::parse('2018-06-15 12:34:00', 'UTC');
$move = Carbon::now('UTC');
$toDisplay = $martinDateFactory->make($gameStart)->isoFormat('lll')."\n".
    $martinDateFactory->make($move)->calendar()."\n";
$notificationForJohn = $johnDateFactory->make($gameStart)->isoFormat('lll')."\n".
    $johnDateFactory->make($move)->calendar()."\n";)}}
{{::exec(echo $toDisplay;)}}
/*
{{eval}}*/

{{::exec(echo $notificationForJohn;)}}
/*
{{eval}}*/

```

You can call any static Carbon method on a factory (make, now, yesterday, tomorrow, parse, create, etc.) Factory (and FactoryImmutable that generates CarbonImmutable instances) are the best way to keep things organized and isolated. As often as possible we recommend you to work with UTC dates, then apply locally (or with a factory) the timezone and the language before displaying dates to the user.

What factory actually do is using the method name as static constructor then call `settings()` method which is a way to group in one call settings of locale, timezone, months/year overflow, etc. ([See references for complete list.](#doc-method-Carbon-settings))

```php
{{::lint($factory = new Factory([
    'locale' => 'fr_FR',
    'timezone' => 'Europe/Paris',
]);
$factory->now(); // You can recall $factory as needed to generate new instances with same settings
// is equivalent to:
Carbon::now()->settings([
    'locale' => 'fr_FR',
    'timezone' => 'Europe/Paris',
]);
// Important note: timezone setting calls ->shiftTimezone() and not ->setTimezone(),
// It means it does not just set the timezone, but shift the time too:
)}}
{{::exec(echo Carbon::today()->setTimezone('Asia/Tokyo')->format('d/m G\h e');/*pad(76)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::today()->shiftTimezone('Asia/Tokyo')->format('d/m G\h e');/*pad(76)*/)}} // {{eval}}

// You can find back which factory created a given object:
{{::lint($a = $factory->now();
$b = Carbon::now();
)}}
{{::exec(var_dump($a->getClock()->unwrap() === $factory);/*pad(49)*/)}} // {{eval}}
{{::exec(var_dump($b->getClock());/*pad(49)*/)}} // {{eval}}

```

`settings()` also allow to pass local macros:

```php
{{::exec($date = Carbon::parse('Today 12:34:56')->settings([
    'macros' => [
        'lastSecondDigit' => fn () => self::this()->second % 10,
    ],
]);

echo $date->lastSecondDigit();/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($date->hasLocalMacro('lastSecondDigit'));/*pad(54)*/)}} // {{eval}}
// You can also retrieve the macro closure using ->getLocalMacro('lastSecondDigit')

```

Factory settings can be changed afterward with `setSettings(array $settings)` or to merge new settings with existing ones `mergeSettings(array $settings)` and the class to generate can be initialized as the second argument of the construct then changed later with `setClassName(string $className)`.

```php
{{::lint($factory = new Factory(['locale' => 'ja'], CarbonImmutable::class);
)}}
{{::exec(var_dump($factory->now()->locale);/*pad(76)*/)}} // {{eval}}
{{::exec(var_dump(get_class($factory->now()));/*pad(76)*/)}} // {{eval}}

{{::lint(class MyCustomCarbonSubClass extends Carbon { /* ... */ }
$factory
    ->setSettings(['locale' => 'zh_CN'])
    ->setClassName(MyCustomCarbonSubClass::class);
)}}
{{::exec(var_dump($factory->now()->locale);/*pad(76)*/)}} // {{eval}}
{{::exec(var_dump(get_class($factory->now()));/*pad(76)*/)}} // {{eval}}

```

Previously there was `Carbon::setLocale` that set globally the locale. But as for our other static setters, we highly discourage you to use it. It breaks the principle of isolation because the configuration will apply for every class that uses Carbon.

`->isoFormat(string $format): string` use ISO format rather than PHP-specific format and use inner translations rather than language packages you need to install on every machine where you deploy your application. `isoFormat` method is compatible with [momentjs format method](https://momentjs.com/), it means you can use same format strings as you may have used in moment from front-end or node.js. Here are some examples:

```php
{{::lint($date = Carbon::parse('2018-06-15 17:34:15.984512', 'UTC');)}}
{{::exec(echo $date->isoFormat('MMMM Do YYYY, h:mm:ss a');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('dddd');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('MMM Do YY');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('YYYY [escaped] YYYY');/*pad(40)*/)}} // {{eval}}

```

You can also create date from ISO formatted strings:

```php
{{::lint($date = Carbon::createFromIsoFormat('!YYYY-MMMM-D h:mm:ss a', '2019-January-3 6:33:24 pm', 'UTC');)}}
{{::exec(echo $date->isoFormat('M/D/YY HH:mm');)}} // {{eval}}

```

`->isoFormat` use contextualized methods for day names and month names as they can have multiple forms in some languages, see the following examples:

```php
{{::lint($date = Carbon::parse('2018-03-16')->locale('uk');)}}
{{::exec(echo $date->getTranslatedDayName('[в] dddd');/*pad(40)*/)}} // {{eval}}
// By providing a context, we're saying translate day name like in a format such as [в] dddd
// So the context itself has to be translated first consistently.
echo "\n";
{{::exec(echo $date->getTranslatedDayName('[наступної] dddd');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->getTranslatedDayName('dddd, MMM');/*pad(40)*/)}} // {{eval}}
echo "\n";
// The same goes for short/minified variants:
{{::exec(echo $date->getTranslatedShortDayName('[наступної] dd');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->getTranslatedMinDayName('[наступної] ddd');/*pad(40)*/)}} // {{eval}}
echo "\n";

// And the same goes for months
{{::lint($date->locale('ru');)}}
{{::exec(echo $date->getTranslatedMonthName('Do MMMM');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->getTranslatedMonthName('MMMM YYYY');/*pad(40)*/)}} // {{eval}}
echo "\n";
// Short variant
{{::exec(echo $date->getTranslatedShortMonthName('Do MMM');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->getTranslatedShortMonthName('MMM YYYY');/*pad(40)*/)}} // {{eval}}
echo "\n";

// And so you can force a different context to get those variants:
{{::exec(echo $date->isoFormat('Do MMMM');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('MMMM YYYY');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('Do MMMM', 'MMMM YYYY');/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->isoFormat('MMMM YYYY', 'Do MMMM');/*pad(40)*/)}} // {{eval}}
echo "\n";

```

Here is the complete list of available replacements (examples given with `{{::lint($date = Carbon::parse('2017-01-05 17:04:05.084512');)}}`):

{{code::each(array\_filter(array\_keys(Carbon::getIsoUnits()), function ($code) { return !preg\_match('/^hmm/i', $code); }))}} {{::endEach}}

| Code | Example | Description |
| --- | --- | --- |
| {{eval(echo $code;)}} | {{eval(echo $date->isoFormat($code);)}} | {{eval(echo $date->describeIsoFormat($code);)}} |

Some macro-formats are also available. Here are examples of each in some languages:

{{locale::each($sampleLocales = \['en', 'fr', 'ja', 'hr'\])}} {{::endEach}} {{code::each(array\_keys($isoFormats = ($date = Carbon::parse('2017-01-05 17:04:05.084512'))->getIsoFormats()))}} {{::endEach}}

| Code | {{eval(echo $locale;)}} |
| --- | --- |
| `{{eval(echo $code;)}}`  
{{eval(echo preg\_match('/^L+$/', $code) ? '  
`'.strtolower($code).'`' : '';)}} | {{eval(echo $date->locale($sampleLocales\[0\])->getIsoFormats()\[$code\];)}}  
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '  
'.$date->isoFormat(strtolower($code)) : '';)}} | {{eval(echo $date->locale($sampleLocales\[1\])->getIsoFormats()\[$code\];)}}  
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '  
'.$date->isoFormat(strtolower($code)) : '';)}} | {{eval(echo $date->locale($sampleLocales\[2\])->getIsoFormats()\[$code\];)}}  
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '  
'.$date->isoFormat(strtolower($code)) : '';)}} | {{eval(echo $date->locale($sampleLocales\[3\])->getIsoFormats()\[$code\];)}}  
{{eval(echo $date->isoFormat($code);)}} {{eval(echo preg\_match('/^L+$/', $code) ? '  
'.$date->isoFormat(strtolower($code)) : '';)}} |

When you use macro-formats with `createFromIsoFormat` you can specify a locale to select which language the macro-format should be searched in.

```php
{{::lint($date = Carbon::createFromIsoFormat('LLLL', 'Monday 11 March 2019 16:28', null, 'fr');)}}
{{::exec(echo $date->isoFormat('M/D/YY HH:mm');)}} // {{eval}}

```

Another usefull translated method is `calendar($referenceTime = null, array $formats = []): string`:

```php
{{::lint($date = CarbonImmutable::now();)}}
{{::exec(echo $date->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->sub('1 day 3 hours')->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->sub('3 days 10 hours 23 minutes')->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->sub('8 days')->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->add('1 day 3 hours')->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->add('3 days 10 hours 23 minutes')->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->add('8 days')->calendar();/*pad(60)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->locale('fr')->calendar();/*pad(60)*/)}} // {{eval}}

```

If you know momentjs, then it works the same way. You can pass a reference date as second argument, else now is used. And you can customize one or more formats using the second argument (formats to pass as array keys are: sameDay, nextDay, nextWeek, lastDay, lastWeek and sameElse):

```php
{{::lint($date1 = CarbonImmutable::parse('2018-01-01 12:00:00');
$date2 = CarbonImmutable::parse('2018-01-02 8:00:00');)}}

{{::exec(echo $date1->calendar($date2, [
    'lastDay' => '[Previous day at] LT',
]);)}}
// {{eval}}

```

[Click here](#supported-locales) to get an overview of the {{eval(echo count(Carbon::getAvailableMacroLocales());)}} locales (and {{eval(echo count(Carbon::getAvailableLocales());)}} regional variants) supported by the last Carbon version:

{{locale::each(Carbon::getAvailableMacroLocales())}} {{::endEach}}

| 
Locale

 | 

Language

 | 

Diff syntax

 | 

1-day diff

 | 

2-days diff

 | 

Month names

 | 

Week days

 | 

Units

 | 

Short units

 | 

Period

 |
| --- | --- | --- | --- | --- | --- | --- | --- | --- | --- |
| {{eval(echo $locale;)}} | {{eval(echo (new \\Carbon\\Language($locale))->getIsoName();)}} | {{eval(echo Carbon::localeHasDiffSyntax($locale) ? '✅' : '❌';)}} | {{eval(echo Carbon::localeHasDiffOneDayWords($locale) ? '✅' : '❌';)}} | {{eval(echo Carbon::localeHasDiffTwoDayWords($locale) ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' || Carbon::parse('january')->monthName !== Carbon::parse('january')->locale($locale)->monthName || Carbon::parse('march')->monthName !== Carbon::parse('march')->locale($locale)->monthName ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' || Carbon::parse('monday')->dayName !== Carbon::parse('monday')->locale($locale)->dayName || Carbon::parse('sunday')->dayName !== Carbon::parse('sunday')->locale($locale)->dayName ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' || Carbon::now()->translate('month') !== Carbon::now()->locale($locale)->translate('month') || Carbon::now()->translate('day') !== Carbon::now()->locale($locale)->translate('day') ? '✅' : '❌';)}} | {{eval(echo substr($locale, 0, 2) === 'en' || Carbon::now()->translate('m') !== Carbon::now()->locale($locale)->translate('m') || Carbon::now()->translate('d') !== Carbon::now()->locale($locale)->translate('d') ? '✅' : '❌';)}} | {{eval(echo Carbon::localeHasPeriodSyntax($locale) ? '✅' : '❌';)}} |

If you can add missing translations or missing languages, [please go to translation tool](/contribute/translate/), your help is welcome.

Note that if you use Laravel, the locale will be automatically set according to current last `App:setLocale` execution. So `diffForHumans`, `isoFormat`, `translatedFormat` and localized properties such as `->dayName` or `->monthName` will be localized transparently.

All Carbon, CarbonImmutable, CarbonInterval or CarbonPeriod instances are linked by default to a `Carbon\Translator` instance handled by `FactoryImmutable::getDefaultInstance()` (The one changing when calling the static method `::setLocale()` on one of those classes). You can get and/or change it using `getLocalTranslator()`/`setLocalTranslator(Translator $translator)`.

If you prefer the [`date()` pattern](https://php.net/manual/en/function.date.php), you can use `translatedFormat()` which works like [`format()`](https://php.net/manual/en/datetime.format.php) but translate the string using the current locale.

```php
{{::lint($date = Carbon::parse('2018-03-16 15:45')->locale('uk');)}}

{{::exec(echo $date->translatedFormat('g:i a l jS F Y');/*pad(40)*/)}} // {{eval}}

```

Be warned that some letters like `W` are not supported because they are not safely translatable and `translatedFormat` offers shorter syntax but less possibilities than `isoFormat()`.

You can customize the behavior of the `format()` method to use any other method or a custom one instead of the native method from the PHP DateTime class:

```php
{{::lint($date = Carbon::parse('2018-03-16 15:45')->locale('ja');)}}

{{::exec(echo $date->format('g:i a l jS F Y');/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($date->settings(['formatFunction' => 'translatedFormat']);)}}

{{::exec(echo $date->format('g:i a l jS F Y');/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($date->settings(['formatFunction' => 'isoFormat']);)}}

{{::exec(echo $date->format('LL');/*pad(40)*/)}} // {{eval}}
echo "\n";

// When you set a custom format() method you still can access the native method using rawFormat()
{{::exec(echo $date->rawFormat('D');/*pad(40)*/)}} // {{eval}}

```

You can translate a string from a language to another using dates translations available in Carbon:

```php
{{::exec(echo Carbon::translateTimeString('mercredi 8 juillet', 'fr', 'nl');)}}
// {{eval}}
echo "\n";

// You can select translations to use among available constants:
// - CarbonInterface::TRANSLATE_MONTHS
// - CarbonInterface::TRANSLATE_DAYS
// - CarbonInterface::TRANSLATE_UNITS
// - CarbonInterface::TRANSLATE_MERIDIEM
// - CarbonInterface::TRANSLATE_ALL (all above)
// You can combine them with pipes: like below (translate units and days but not months and meridiem):
{{::exec(echo Carbon::translateTimeString('mercredi 8 juillet + 3 jours', 'fr', 'nl', CarbonInterface::TRANSLATE_DAYS | CarbonInterface::TRANSLATE_UNITS);)}}
// {{eval}}

```

If input locale is not specified, `Carbon::getLocale()` is used instead. If output locale is not specified, `"en"` is used instead. You also can translate using the locale of the instance with:

```php
{{::exec(echo Carbon::now()->locale('fr')->translateTimeStringTo('mercredi 8 juillet + 3 jours', 'nl');)}}
// {{eval}}

```

You can use strings in any language directly to create a date object with `parseFromLocale`:

```php
{{::lint($date = Carbon::parseFromLocale('mercredi 6 mars 2019 + 3 jours', 'fr', 'UTC');)}} // timezone is optional
// 'fr' stands for French but can be replaced with any locale code.
// if you don't pass the locale parameter, Carbon::getLocale() (current global locale) is used.

{{::exec(echo $date->isoFormat('LLLL');)}} // {{eval}}

```

You can also use "today", "today at 8:00", "yesterday", "after tomorrow", etc. equivalents in the given language.

Or with custom format using `createFromLocaleFormat` (use the [`date()` pattern](https://php.net/manual/en/function.date.php) for replacements):

```php
{{::lint($date = Carbon::createFromLocaleFormat('!d/F/y', 'fr', '25/Août/19', 'Europe/Paris');)}} // timezone is optional

{{::exec(echo $date->isoFormat('LLLL');)}} // {{eval}}

```

The equivalent method using ISO format is `createFromLocaleIsoFormat`:

```php
{{::lint($date = Carbon::createFromLocaleIsoFormat('!DD/MMMM/YY', 'fr', '25/Août/19', 'Europe/Paris');)}} // timezone is optional

{{::exec(echo $date->isoFormat('LLLL');)}} // {{eval}}

```

To get some interesting info about languages (such as complete ISO name or native name, region (for example to be displayed in a languages selector), you can use `getAvailableLocalesInfo`.

```php
{{::lint($zhTwInfo = Carbon::getAvailableLocalesInfo()['zh_TW'];
$srCyrlInfo = Carbon::getAvailableLocalesInfo()['sr_Cyrl'];
$caInfo = Carbon::getAvailableLocalesInfo()['ca'];)}}

{{::exec(var_dump($zhTwInfo->getId());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getNames());)}}
/*
{{eval}}
*/
{{::exec(var_dump($zhTwInfo->getCode());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getVariant());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getVariant());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getVariantName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getVariantName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getRegion());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getRegion());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getRegionName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getRegionName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getFullIsoName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getFullIsoName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getFullNativeName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getFullNativeName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getIsoName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getIsoName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getNativeName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getNativeName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getNativeDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getNativeDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getNativeDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getFullIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getFullIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getFullIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($zhTwInfo->getFullNativeDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getFullNativeDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($caInfo->getFullNativeDescription());/*pad(50)*/)}} // {{eval}}

{{::lint($srCyrlInfo->setIsoName('foo, bar')->setNativeName('biz, baz');)}}
{{::exec(var_dump($srCyrlInfo->getIsoName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getFullIsoName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getFullIsoDescription());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getNativeName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getFullNativeName());/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($srCyrlInfo->getFullNativeDescription());/*pad(50)*/)}} // {{eval}}

// You can also access directly regions/languages lists:
{{::exec(var_dump(\Carbon\Language::all()['zh']);)}}
/*
{{eval}}
*/
{{::exec(var_dump(\Carbon\Language::regions()['TW']);)}}
/*
{{eval}}
*/

```

If ever you have to change globally the locale for a particular process, you should use `executeWithLocale` to encapsulate this process. This way, even if an exception is thrown the global locale with be set back to its previous value.

```php
{{::exec(
Carbon::executeWithLocale('fr', function () {
    echo CarbonInterval::create(2, 1)->forHumans() . "\n";
    echo Carbon::parse('-2 hours')->diffForHumans();
});)}}
/*
{{eval}}
*/

```

Please let me close this section by thanking some projects that helped us a lot to support more locales, and internationalization features:

*   [jenssegers/date](https://github.com/jenssegers/date): many features were in this project that extends Carbon before being in Carbon itself.
*   [momentjs](https://momentjs.com): many features are inspired by momentjs and made to be compatible with this front-side pair project.
*   [glibc](https://www.gnu.org/software/libc/) was a strong base for adding and checking languages.
*   [svenfuchs/rails-i18n](https://github.com/svenfuchs/rails-i18n) also helped to add and check languages.
*   We used [glosbe.com](https://glosbe.com/) a lot to check translations and fill blanks.

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

# Setters

The following setters are implemented via PHP's `__set()` method. Its good to take note here that none of the setters, with the obvious exception of explicitly setting the timezone, will change the timezone of the instance. Specifically, setting the timestamp will not set the corresponding timezone to UTC.

```php
{{::lint(
$dt = Carbon::now();

$dt->year = 1975;
$dt->month = 13;             // would force year++ and month = 1
$dt->month = 5;
$dt->day = 21;
$dt->hour = 22;
$dt->minute = 32;
$dt->second = 5;

$dt->timestamp = 169957925;  // This will not change the timezone
// Same as:
$dt->setTimestamp(169957925);
$dt->timestamp(169957925);

// Set the timezone via DateTimeZone instance or string
$dt->tz = new \DateTimeZone('Europe/London');
$dt->tz = 'Europe/London';

// The ->timezone is also available for backward compatibility but
// it will be overridden by native php DateTime class as soon as
// the object is dump (passed foreach, serialize, var_export, clone, etc.)
// making the Carbon setter inefficient, if it happen, you can cleanup
// those overridden properties by calling ->cleanupDumpProperties() on
// the instance, but we rather recommend to simply use ->tz instead
// of ->timezone everywhere.

// verbose way:
$dt->setYear(2001);
)}}
{{::exec(echo $dt->year;/*pad(20)*/)}} // {{eval}}
echo "\n";

{{::lint(
// set/get method:
$dt->year(2002);
)}}
{{::exec(echo $dt->year();/*pad(20)*/)}} // {{eval}}
echo "\n";

{{::lint(
// dynamic way:
$dt->set('year', 2003);
)}}
{{::exec(echo $dt->get('year');/*pad(20)*/)}} // {{eval}}
echo "\n";

// these methods exist for every units even for calculated properties such as:
{{::exec(echo $dt->dayOfYear(35)->format('Y-m-d');/*pad(20)*/)}} // {{eval}}

```

# Weeks

If you are familiar with momentjs, you will find all week methods working the same. Most of them have an iso{Method} variant. Week methods follow the rules of the current locale (for example with en\_US, the default locale, the first day of the week is Sunday, and the first week of the year is the one that contains January 1st). ISO methods follow the ISO 8601 norm, meaning weeks start with Monday and the first week of the year is the one containing January 4th.

```php
{{::lint(
$en = CarbonImmutable::now()->locale('en_US');
$ar = CarbonImmutable::now()->locale('ar');
)}}

{{::exec(var_dump($en->firstWeekDay);/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->lastWeekDay);/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->startOfWeek()->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->endOfWeek()->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}

{{::lint(
echo "-----------\n";

// We still can force to use an other day as start/end of week
$start = $en->startOfWeek(Carbon::TUESDAY);
$end = $en->endOfWeek(Carbon::MONDAY);
)}}
{{::exec(var_dump($start->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($end->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}

{{::lint(
echo "-----------\n";
)}}

{{::exec(var_dump($ar->firstWeekDay);/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($ar->lastWeekDay);/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($ar->startOfWeek()->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($ar->endOfWeek()->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}

{{::lint(
$en = CarbonImmutable::parse('2015-02-05'); // use en_US as default locale

echo "-----------\n";
)}}

{{::exec(var_dump($en->weeksInYear());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeeksInYear());/*pad(54)*/)}} // {{eval}}

{{::lint(
$en = CarbonImmutable::parse('2017-02-05');

echo "-----------\n";
)}}

{{::exec(var_dump($en->week());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeek());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->week(1)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeek(1)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}

// weekday/isoWeekday are meant to be used with days constants
{{::exec(var_dump($en->weekday());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekday());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->weekday(CarbonInterface::WEDNESDAY)
    ->format('Y-m-d H:i'));/*pad(105)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekday(CarbonInterface::WEDNESDAY)
    ->format('Y-m-d H:i'));/*pad(108)*/)}} // {{eval}}

// getDaysFromStartOfWeek/setDaysFromStartOfWeek return and take a number of days
// taking the current locale into account
{{::lint(
$date = CarbonImmutable::parse('2022-12-05')->locale('en_US');
)}}

{{::exec(var_dump($date->getDaysFromStartOfWeek());/*pad(62)*/)}} // {{eval}}

{{::lint(
$date = CarbonImmutable::parse('2022-12-05')->locale('de_AT');
)}}

{{::exec(var_dump($date->getDaysFromStartOfWeek());/*pad(62)*/)}} // {{eval}}
{{::exec(var_dump($date->setDaysFromStartOfWeek(3)->format('Y-m-d'));/*pad(62)*/)}} // {{eval}}

// Or specify explicitly the first day of week
{{::exec(var_dump($date->setDaysFromStartOfWeek(3, CarbonInterface::SUNDAY)->format('Y-m-d'));/*pad(62)*/)}} // {{eval}}

{{::lint(
$en = CarbonImmutable::parse('2017-01-01');

echo "-----------\n";
)}}

{{::exec(var_dump($en->weekYear());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekYear());/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->weekYear(2016)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekYear(2016)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->weekYear(2015)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekYear(2015)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}

{{::lint(
// Note you still can force first day of week and year to use:
$en = CarbonImmutable::parse('2017-01-01');

echo "-----------\n";
)}}

{{::exec(var_dump($en->weeksInYear(null, 6, 12));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeeksInYear(null, 6, 12));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->week(null, 6, 12));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeek(null, 6, 12));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->weekYear(null, 6, 12));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekYear(null, 6, 12));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->weekYear(2016, 6, 12)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
{{::exec(var_dump($en->isoWeekYear(2016, 6, 12)->format('Y-m-d H:i'));/*pad(54)*/)}} // {{eval}}
// Then you can see using a method or its ISO variant return identical results

```

# Fluent Setters

You can call any base unit as a setter or some grouped setters:

```php
{{::lint(
$dt = Carbon::now();

$dt->year(1975)->month(5)->day(21)->hour(22)->minute(32)->second(5)->toDateTimeString();
$dt->setDate(1975, 5, 21)->setTime(22, 32, 5)->toDateTimeString();
$dt->setDate(1975, 5, 21)->setTimeFromTimeString('22:32:05')->toDateTimeString();
$dt->setDateTime(1975, 5, 21, 22, 32, 5)->toDateTimeString();

// All allow microsecond as optional argument
$dt->year(1975)->month(5)->day(21)->hour(22)->minute(32)->second(5)->microsecond(123456)->toDateTimeString();
$dt->setDate(1975, 5, 21)->setTime(22, 32, 5, 123456)->toDateTimeString();
$dt->setDate(1975, 5, 21)->setTimeFromTimeString('22:32:05.123456')->toDateTimeString();
$dt->setDateTime(1975, 5, 21, 22, 32, 5, 123456)->toDateTimeString();

$dt->timestamp(169957925); // Note: timestamps are UTC but do not change the date timezone

$dt->timezone('Europe/London')->tz('America/Toronto')->setTimezone('America/Vancouver');
)}}

```

You also can set date and time separately from other DateTime/Carbon objects:

```php
{{::lint(
$source1 = new Carbon('2010-05-16 22:40:10.1');

$dt = new Carbon('2001-01-01 01:01:01.2');
$dt->setTimeFrom($source1);
)}}

{{::exec(echo $dt;)}} // {{eval}}

{{::lint(
$source2 = new \DateTime('2013-09-01 09:22:56.2');

$dt->setDateFrom($source2);
)}}

{{::exec(echo $dt;)}} // {{eval}}

{{::lint($dt->setDateTimeFrom($source2);)}} // set date and time including microseconds
// bot not settings as locale, timezone, options.

```

# String Formatting

All of the available `toXXXString()` methods rely on the base class method [DateTime::format()](https://www.php.net/manual/en/datetime.format.php). You'll notice the `__toString()` method is defined which allows a Carbon instance to be printed as a pretty date time string when used in a string context.

```php
{{::lint($dt = Carbon::create(1975, 12, 25, 14, 15, 16);)}}

{{::exec(var_dump($dt->toDateTimeString() == $dt);/*pad(50)*/)}} // {{eval}} => uses __toString()
{{::exec(echo $dt->toDateString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->toFormattedDateString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->toFormattedDayDateString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->toTimeString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->toDateTimeString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt->toDayDateTimeString();/*pad(50)*/)}} // {{eval}}

// ... of course format() is still available
{{::exec(echo $dt->format('l jS \\of F Y h:i:s A');/*pad(50)*/)}} // {{eval}}

// The reverse hasFormat method allows you to test if a string looks like a given format
{{::exec(var_dump(Carbon::hasFormat('Thursday 25th December 1975 02:15:16 PM', 'l jS F Y h:i:s A'));)}} // {{eval}}

```

You can also set the default \_\_toString() format (which defaults to `Y-m-d H:i:s`) that's used when [type juggling](https://www.php.net/manual/en/language.types.type-juggling.php) occurs.

```php
{{::exec(echo $dt;/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::lint(
$dt->settings([
    'toStringFormat' => 'jS \o\f F, Y g:i:s a',
]);
)}}
{{::exec(echo $dt;/*pad(50)*/)}} // {{eval}}

// As any setting, you can get the current value for a given date using:
{{::exec(var_dump($dt->getSettings());)}}
/*
{{eval}}*/

```

As part of the settings `'toStringFormat'` can be used in factories too. It also may be a closure, so you can run any code on string cast.

If you use Carbon 1 or want to apply it globally as default format, you can use:

```php
{{::lint(
$dt = Carbon::create(1975, 12, 25, 14, 15, 16);
Carbon::setToStringFormat('jS \o\f F, Y g:i:s a');
)}}
{{::exec(echo $dt;/*pad(50)*/)}} // {{eval}}
echo "\n";
{{::lint(
Carbon::resetToStringFormat();
)}}
{{::exec(echo $dt;/*pad(50)*/)}} // {{eval}}

```

Note: For localization support see the [Localization](#api-localization) section.

# Common Formats

The following are wrappers for the common formats provided in the [DateTime class](https://www.php.net/manual/en/class.datetime.php).

```php
{{::lint($dt = Carbon::createFromFormat('Y-m-d H:i:s.u', '2019-02-01 03:45:27.612584');)}}

// $dt->toAtomString() is the same as $dt->format(\DateTime::ATOM);
{{::exec(echo $dt->toAtomString();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toCookieString();/*pad(35)*/)}} // {{eval}}

{{::exec(echo $dt->toIso8601String();/*pad(35)*/)}} // {{eval}}
// Be aware we chose to use the full-extended format of the ISO 8601 norm
// Natively, \DateTime::ISO8601 format is not compatible with ISO-8601 as it
// is explained here in the PHP documentation:
// https://php.net/manual/class.datetime.php#datetime.constants.iso8601
// We consider it as a PHP mistake and chose not to provide method for this
// format, but you still can use it this way:
{{::exec(echo $dt->format(\DateTime::ISO8601);/*pad(35)*/)}} // {{eval}}

{{::exec(echo $dt->toISOString();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toJSON();/*pad(35)*/)}} // {{eval}}

{{::exec(echo $dt->toIso8601ZuluString();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toDateTimeLocalString();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc822String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc850String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc1036String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc1123String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc2822String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc3339String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRfc7231String();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toRssString();/*pad(35)*/)}} // {{eval}}
{{::exec(echo $dt->toW3cString();/*pad(35)*/)}} // {{eval}}

```

# Conversion

```php
{{::lint($dt = Carbon::createFromFormat('Y-m-d H:i:s.u', '2019-02-01 03:45:27.612584');)}}

{{::exec(var_dump($dt->toArray());)}}
/*
{{eval}}
*/

{{::exec(var_dump($dt->toObject());)}}
/*
{{eval}}
*/

{{::exec(var_dump($dt->toDate()); // Same as $dt->toDateTime())}}
/*
{{eval}}
*/

// Note than both Carbon and CarbonImmutable can be cast
// to both DateTime and DateTimeImmutable
{{::exec(var_dump($dt->toDateTimeImmutable());)}}
/*
{{eval}}
*/

{{::exec(class MySubClass extends Carbon {}
// MySubClass can be any class implementing CarbonInterface or a public static instance() method.

$copy = $dt->cast(MySubClass::class);
// Since 2.23.0, cast() can also take as argument any class that extend DateTime or DateTimeImmutable

echo get_class($copy).': '.$copy; // Same as MySubClass::instance($dt))}}
/*
{{eval}}
*/

```

You can use the method `carbonize` to transform many things into a `Carbon` instance based on a given source instance used as reference on need. It returns a new instance.

```php
{{::lint($dt = Carbon::createFromFormat('Y-m-d H:i:s.u', '2019-02-01 03:45:27.612584', 'Europe/Paris');)}}

// Can take a date string and will apply the timezone from reference object
{{::exec(var_dump($dt->carbonize('2019-03-21'));)}}
/*
{{eval}}
*/

// If you pass a DatePeriod or CarbonPeriod, it will copy the period start
{{::exec(var_dump($dt->carbonize(CarbonPeriod::create('2019-12-10', '2020-01-05')));)}}
/*
{{eval}}
*/

// If you pass a DateInterval or CarbonInterval, it will add the interval to
// the reference object
{{::exec(var_dump($dt->carbonize(CarbonInterval::days(3)));)}}
/*
{{eval}}
*/

```

# Comparison

Simple comparison is offered up via the following functions. Remember that the comparison is done in the UTC timezone so things aren't always as they seem.

```php
{{::exec(echo Carbon::now()->tzName;/*pad(50)*/)}} // {{eval}}
{{::lint($first = Carbon::create(2012, 9, 5, 23, 26, 11);)}}
{{::lint($second = Carbon::create(2012, 9, 5, 20, 26, 11, 'America/Vancouver');)}}

{{::exec(echo $first->toDateTimeString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $first->tzName;/*pad(50)*/)}} // {{eval}}
{{::exec(echo $second->toDateTimeString();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $second->tzName;/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump($first->equalTo($second));/*pad(50)*/)}} // {{eval}}
// equalTo is also available on CarbonInterval and CarbonPeriod
{{::exec(var_dump($first->notEqualTo($second));/*pad(50)*/)}} // {{eval}}
// notEqualTo is also available on CarbonInterval and CarbonPeriod
{{::exec(var_dump($first->greaterThan($second));/*pad(50)*/)}} // {{eval}}
// greaterThan is also available on CarbonInterval
{{::exec(var_dump($first->greaterThanOrEqualTo($second));/*pad(50)*/)}} // {{eval}}
// greaterThanOrEqualTo is also available on CarbonInterval
{{::exec(var_dump($first->lessThan($second));/*pad(50)*/)}} // {{eval}}
// lessThan is also available on CarbonInterval
{{::exec(var_dump($first->lessThanOrEqualTo($second));/*pad(50)*/)}} // {{eval}}
// lessThanOrEqualTo is also available on CarbonInterval

{{::lint($first->setDateTime(2012, 1, 1, 0, 0, 0);)}}
{{::lint($second->setDateTime(2012, 1, 1, 0, 0, 0);/*pad(50)*/)}} // Remember tz is 'America/Vancouver'

{{::exec(var_dump($first->equalTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->notEqualTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->greaterThan($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->greaterThanOrEqualTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->lessThan($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->lessThanOrEqualTo($second));/*pad(50)*/)}} // {{eval}}

// All have short hand aliases and PHP equivalent code:

{{::exec(var_dump($first->eq($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->equalTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first == $second);/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump($first->ne($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->notEqualTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first != $second);/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump($first->gt($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->greaterThan($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->isAfter($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first > $second);/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump($first->gte($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->greaterThanOrEqualTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first >= $second);/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump($first->lt($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->lessThan($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->isBefore($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first < $second);/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump($first->lte($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first->lessThanOrEqualTo($second));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($first <= $second);/*pad(50)*/)}} // {{eval}}

```

Those methods use natural comparisons offered by PHP `$date1 == $date2` so all of them will ignore milli/micro-seconds before PHP 7.1, then take them into account starting with 7.1.

To determine if the current instance is between two other instances you can use the aptly named `between()` method (or `isBetween()` alias). The third parameter indicates if an equal to comparison should be done. The default is true which determines if its between or equal to the boundaries.

```php
{{::lint($first = Carbon::create(2012, 9, 5, 1);)}}
{{::lint($second = Carbon::create(2012, 9, 5, 5);)}}
{{::exec(var_dump(Carbon::create(2012, 9, 5, 3)->between($first, $second));/*pad(75)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::create(2012, 9, 5, 5)->between($first, $second));/*pad(75)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::create(2012, 9, 5, 5)->between($first, $second, false));/*pad(75)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::create(2012, 9, 5, 5)->isBetween($first, $second, false));/*pad(75)*/)}} // {{eval}}
// Rather than passing false as a third argument, you can use betweenIncluded and betweenExcluded
{{::exec(var_dump(Carbon::create(2012, 9, 5, 5)->betweenIncluded($first, $second));/*pad(75)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::create(2012, 9, 5, 5)->betweenExcluded($first, $second));/*pad(75)*/)}} // {{eval}}
// All those methods are also available on CarbonInterval

```

Woah! Did you forget min() and max() ? Nope. That is covered as well by the suitably named `min()` and `max()` methods or `minimum()` and `maximum()` aliases. As usual the default parameter is now if null is specified.

```php
{{::lint($dt1 = Carbon::createMidnightDate(2012, 1, 1);)}}
{{::lint($dt2 = Carbon::createMidnightDate(2014, 1, 30);)}}
{{::exec(echo $dt1->min($dt2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt1->minimum($dt2);/*pad(50)*/)}} // {{eval}}
// Also works with string
{{::exec(echo $dt1->minimum('2014-01-30');/*pad(50)*/)}} // {{eval}}

{{::lint($dt1 = Carbon::createMidnightDate(2012, 1, 1);)}}
{{::lint($dt2 = Carbon::createMidnightDate(2014, 1, 30);)}}
{{::exec(echo $dt1->max($dt2);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt1->maximum($dt2);/*pad(50)*/)}} // {{eval}}

// now is the default param
{{::lint($dt1 = Carbon::createMidnightDate(2000, 1, 1);)}}
{{::exec(echo $dt1->max();/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt1->maximum();/*pad(50)*/)}} // {{eval}}

// Remember min and max PHP native function work fine with dates too:
{{::exec(echo max(Carbon::create('2002-03-15'), Carbon::create('2003-01-07'), Carbon::create('2002-08-25'));/*pad(50)*/)}} // {{eval}}
{{::exec(echo min(Carbon::create('2002-03-15'), Carbon::create('2003-01-07'), Carbon::create('2002-08-25'));/*pad(50)*/)}} // {{eval}}
// This way you can pass as many dates as you want and get no ambiguities about parameters order

{{::lint(
$dt1 = Carbon::createMidnightDate(2010, 4, 1);
$dt2 = Carbon::createMidnightDate(2010, 3, 28);
$dt3 = Carbon::createMidnightDate(2010, 4, 16);
)}}

// returns the closest of two date (no matter before or after)
{{::exec(echo $dt1->closest($dt2, $dt3);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt2->closest($dt1, $dt3);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt3->closest($dt2, $dt1);/*pad(50)*/)}} // {{eval}}

// returns the farthest of two date (no matter before or after)
{{::exec(echo $dt1->farthest($dt2, $dt3);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt2->farthest($dt1, $dt3);/*pad(50)*/)}} // {{eval}}
{{::exec(echo $dt3->farthest($dt2, $dt1);/*pad(50)*/)}} // {{eval}}

```

To handle the most used cases there are some simple helper functions that hopefully are obvious from their names. For the methods that compare to `now()` (ex. isToday()) in some manner, the `now()` is created in the same timezone as the instance.

```php
{{::lint(
$dt = Carbon::now();
$dt2 = Carbon::createFromDate(1987, 4, 23);

$dt->isSameAs('w', $dt2); // w is the date of the week, so this will return true if $dt and $dt2
                          // the same day of week (both monday or both sunday, etc.)
                          // you can use any format and combine as much as you want.
$dt->isFuture();
$dt->isNowOrFuture();
$dt->isPast();
$dt->isNowOrPast();

$dt->isSameYear($dt2);
$dt->isCurrentYear();
$dt->isNextYear();
$dt->isLastYear();
$dt->isLongIsoYear(); // see https://en.wikipedia.org/wiki/ISO_8601#Week_dates
Carbon::create(2015)->isLongYear(); // isLongIsoYear() check a given date,
    // while isLongYear() will ignore month/day and just check a given year number
$dt->isLeapYear();

$dt->isSameQuarter($dt2); // same quarter of the same year of the given date
$dt->isSameQuarter($dt2, false); // same quarter (3 months) no matter the year of the given date
$dt->isCurrentQuarter();
$dt->isNextQuarter(); // date is in the next quarter
$dt->isLastQuarter(); // in previous quarter

$dt->isSameMonth($dt2); // same month of the same year of the given date
$dt->isSameMonth($dt2, false); // same month no matter the year of the given date
$dt->isCurrentMonth();
$dt->isNextMonth();
$dt->isLastMonth();

$dt->isWeekday();
$dt->isWeekend();
$dt->isMonday();
$dt->isTuesday();
$dt->isWednesday();
$dt->isThursday();
$dt->isFriday();
$dt->isSaturday();
$dt->isSunday();
$dt->isDayOfWeek(Carbon::SATURDAY); // is a saturday
$dt->isLastOfMonth(); // is the last day of the month

$dt->is('Sunday');
$dt->is('June');
$dt->is('2019');
$dt->is('12:23');
$dt->is('2 June 2019');
$dt->is('06-02');

$dt->isSameDay($dt2); // Same day of same month of same year
$dt->isCurrentDay();
$dt->isYesterday();
$dt->isToday();
$dt->isTomorrow();
$dt->isNextWeek();
$dt->isLastWeek();

$dt->isSameHour($dt2);
$dt->isCurrentHour();
$dt->isSameMinute($dt2);
$dt->isCurrentMinute();
$dt->isSameSecond($dt2);
$dt->isCurrentSecond();

$dt->isStartOfDay(); // check if hour is 00:00:00
$dt->isMidnight(); // check if hour is 00:00:00 (isStartOfDay alias)
$dt->isEndOfDay(); // check if hour is 23:59:59
$dt->isMidday(); // check if hour is 12:00:00 (or other midday hour set with Carbon::setMidDayAt())
)}}
{{::lint($born = Carbon::createFromDate(1987, 4, 23);)}}
{{::lint($noCake = Carbon::createFromDate(2014, 9, 26);)}}
{{::lint($yesCake = Carbon::createFromDate(2014, 4, 23);)}}
{{::lint($overTheHill = Carbon::now()->subYears(50);)}}
{{::exec(var_dump($born->isBirthday($noCake));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($born->isBirthday($yesCake));/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump($overTheHill->isBirthday());/*pad(50)*/)}} // {{eval}} -> default compare it to today!

// isStartOfX, isEndOfX, isCurrentX, isSameX, isNextX and isLastX are available for each unit

```

The methods `isStartOfMillisecond`, `isEndOfMillisecond`, `isStartOfSecond`, `isEndOfSecond`, `isStartOfMinute`, `isEndOfMinute`, `isStartOfHour`, `isEndOfHour`, `isStartOfDay` and `isEndOfDay` will use 1 microsecond as the default interval to be considered as the start/end, which means `isEndOfDay` returns `true` only for 23:59:59.999999, `false` for 23:59:59.999998.

The methods `isStartOfWeek`, `isEndOfWeek`, `isStartOfMonth`, `isEndOfMonth`, `isStartOfQuarter`, `isEndOfQuarter`, `isStartOfYear`, `isEndOfYear`, `isStartOfDecade`, `isEndOfDecade`, `isStartOfCentury`, `isEndOfCentury`, `isStartOfMillennium` and `isEndOfMillennium` will use 1 day as the default interval to be considered as the start/end. isStartOfUnit

But you can customize this interval: `$date->isStartOfDay(interval: '4 hours')` (`true` if the current time is >= `00:00:00.000000` and <= `03:59:59.999999`).

`$date->isEndOfCentury(interval: Unit::Year)` (`true` if the current year is the last one of a century, i.e. ends with 00).

`isStartOfUnit` and `isEndOfUnit` allow this check with dynamic unit: `$date->isStartOfUnit(Unit::Year, interval: '1 month')`, `$date->isStartOfUnit(Unit::Week, Unit::Hour)`

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

# Difference for Humans

It is easier for humans to read `1 month ago` compared to 30 days ago. This is a common function seen in most date libraries so I thought I would add it here as well. The lone argument for the function is the other Carbon instance to diff against, and of course it defaults to `now()` if not specified.

This method will add a phrase after the difference value relative to the instance and the passed in instance. There are 4 possibilities:

*   When comparing a value in the past to default now:
    *   1 hour ago
    *   5 months ago
*   When comparing a value in the future to default now:
    *   1 hour from now
    *   5 months from now
*   When comparing a value in the past to another value:
    *   1 hour before
    *   5 months before
*   When comparing a value in the future to another value:
    *   1 hour after
    *   5 months after

You may also pass `CarbonInterface::DIFF_ABSOLUTE` as a 2nd parameter to remove the modifiers _ago_, _from now_, etc : `diffForHumans($other, CarbonInterface::DIFF_ABSOLUTE)`, `CarbonInterface::DIFF_RELATIVE_TO_NOW` to get modifiers _ago_ or _from now_, `CarbonInterface::DIFF_RELATIVE_TO_OTHER` to get the modifiers _before_ or _after_ or `CarbonInterface::DIFF_RELATIVE_AUTO` (default mode) to get the modifiers either _ago_/_from now_ if the 2 second argument is null or _before_/_after_ if not.

You may pass `true` as a 3rd parameter to use short syntax if available in the locale used : `diffForHumans($other, CarbonInterface::DIFF_RELATIVE_AUTO, true)`.

You may pass a number between 1 and 6 as a 4th parameter to get the difference in multiple parts (more precise diff) : `diffForHumans($other, CarbonInterface::DIFF_RELATIVE_AUTO, false, 4)`.

The `$other` instance can be a DateTime, a Carbon instance or any object that implement DateTimeInterface, if a string is passed it will be parsed to get a Carbon instance and if `null` is passed, `Carbon::now()` will be used instead.

To avoid having too much argument and mix the order, you can use the verbose methods:

*   `shortAbsoluteDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `longAbsoluteDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `shortRelativeDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `longRelativeDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `shortRelativeToNowDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `longRelativeToNowDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `shortRelativeToOtherDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`
*   `longRelativeToOtherDiffForHumans(DateTimeInterface | null $other = null, int $parts = 1)`

PS: `$other` and `$parts` arguments can be swapped as need.

```php
// The most typical usage is for comments
// The instance is the date the comment was created and its being compared to default now()
{{::exec(echo Carbon::now()->subDays(5)->diffForHumans();/*pad(62)*/)}} // {{eval}}

{{::exec(echo Carbon::now()->diffForHumans(Carbon::now()->subYear());/*pad(62)*/)}} // {{eval}}

{{::lint($dt = Carbon::createFromDate(2011, 8, 1);)}}

{{::exec(echo $dt->diffForHumans($dt->copy()->addMonth());/*pad(72)*/)}} // {{eval}}
{{::exec(echo $dt->diffForHumans($dt->copy()->subMonth());/*pad(72)*/)}} // {{eval}}

{{::exec(echo Carbon::now()->addSeconds(5)->diffForHumans();/*pad(72)*/)}} // {{eval}}

{{::exec(echo Carbon::now()->subDays(24)->diffForHumans();/*pad(72)*/)}} // {{eval}}
{{::exec(echo Carbon::now()->subDays(24)->longAbsoluteDiffForHumans();/*pad(72)*/)}} // {{eval}}

{{::exec(echo Carbon::parse('2019-08-03')->diffForHumans('2019-08-13');/*pad(72)*/)}} // {{eval}}
{{::exec(echo Carbon::parse('2000-01-01 00:50:32')->diffForHumans('@946684800');/*pad(72)*/)}} // {{eval}}

{{::exec(echo Carbon::create(2018, 2, 26, 4, 29, 43)->longRelativeDiffForHumans(Carbon::create(2016, 6, 21, 0, 0, 0), 6);)}} // {{eval}}

```

You can also change the locale of the string using `$date->locale('fr')` before the diffForHumans() call. See the [localization](#api-localization) section for more detail.

Since 2.9.0 diffForHumans() parameters can be passed as an array:

```php
{{::exec(
echo Carbon::now()->diffForHumans(['options' => 0]);)}} // {{eval}}
echo "\n";
// default options:
{{::exec(echo Carbon::now()->diffForHumans(['options' => Carbon::NO_ZERO_DIFF]);)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->diffForHumans(['options' => Carbon::JUST_NOW]);)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->subDay()->diffForHumans(['options' => 0]);)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->subDay()->diffForHumans(['options' => Carbon::ONE_DAY_WORDS]);)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->subDays(2)->diffForHumans(['options' => 0]);)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->subDays(2)->diffForHumans(['options' => Carbon::TWO_DAY_WORDS]);)}} // {{eval}}
echo "\n";

{{::lint(// Options can be combined with pipes
$now = Carbon::now();
)}}

{{::exec(echo $now->diffForHumans(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS]);)}} // {{eval}}
echo "\n";

// Reference date for differences is `now` but you can use any other date (string, DateTime or Carbon instance):
{{::lint($yesterday = $now->copy()->subDay();)}}
{{::exec(echo $now->diffForHumans($yesterday);)}} // {{eval}}
echo "\n";
// By default differences methods produce "ago"/"from now" syntax using default reference (now),
// and "after"/"before" with other references
// But you can customize the syntax:
{{::exec(echo $now->diffForHumans($yesterday, ['syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW]);)}} // {{eval}}
echo "\n";
{{::exec(echo $now->diffForHumans($yesterday, ['syntax' => 0]);)}} // {{eval}}
echo "\n";
{{::exec(echo $yesterday->diffForHumans(['syntax' => CarbonInterface::DIFF_ABSOLUTE]);)}} // {{eval}}
echo "\n";
// Combined with options:
{{::exec(echo $now->diffForHumans($yesterday, [
    'syntax' => CarbonInterface::DIFF_RELATIVE_TO_NOW,
    'options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS,
]);)}} // {{eval}}
echo "\n";

// Other parameters:
{{::exec(echo $now->copy()->subHours(5)->subMinutes(30)->subSeconds(10)->diffForHumans([
    'parts' => 2,
]);)}} // {{eval}}
echo "\n";
{{::exec(echo $now->copy()->subHours(5)->subMinutes(30)->subSeconds(10)->diffForHumans([
    'parts' => 3, // Use -1 or INF for no limit
]);)}} // {{eval}}
echo "\n";
{{::exec(echo $now->copy()->subHours(5)->subMinutes(30)->subSeconds(10)->diffForHumans([
    'parts' => 3,
    'join' => ', ', // join with commas
]);)}} // {{eval}}
echo "\n";
{{::exec(echo $now->copy()->subHours(5)->subMinutes(30)->subSeconds(10)->diffForHumans([
    'parts' => 3,
    'join' => true, // join with natural syntax as per current locale
]);)}} // {{eval}}
echo "\n";
{{::exec(echo $now->copy()->subHours(5)->subMinutes(30)->subSeconds(10)->locale('fr')->diffForHumans([
    'parts' => 3,
    'join' => true, // join with natural syntax as per current locale
]);)}} // {{eval}}
echo "\n";
{{::exec(echo $now->copy()->subHours(5)->subMinutes(30)->subSeconds(10)->diffForHumans([
    'parts' => 3,
    'short' => true, // short syntax as per current locale
]);)}} // {{eval}}
// 'aUnit' option added in 2.13.0 allows you to prefer "a day", "an hour", etc. over "1 day", "1 hour"
// for singular values when it's available in the current locale
{{::exec(echo $now->copy()->subHour()->diffForHumans([
    'aUnit' => true,
]);)}} // {{eval}}

// Before 2.9.0, you need to pass parameters as ordered parameters:
// ->diffForHumans($other, $syntax, $short, $parts, $options)
// and 'join' was not supported

```

If the argument is omitted or set to `null`, only `Carbon::NO_ZERO_DIFF` is enabled. Available options are:

*   `Carbon::ROUND` / `Carbon::CEIL` / `Carbon::FLOOR` (none by default): only one of the 3 can be used at a time (other are ignored) and it requires `'parts'` to be set. By default, once the diff has as parts as `'parts'` setting requested and omit all remaining units.
    *   If `Carbon::ROUND` is enabled, the remaining units are summed and if they are **\>= 0.5** of the last unit of the diff, this unit will be rounded to the upper value.
    *   If `Carbon::CEIL` is enabled, the remaining units are summed and if they are **\> 0** of the last unit of the diff, this unit will be rounded to the upper value.
    *   If `Carbon::FLOOR` is enabled, the last diff unit is rounded down. It makes no difference from the default behavior for `diffForHumans()` as the interval can't have overflow, but this option may be needed when used with `CarbonInterval::forHumans()` (and unchecked intervals that may have 60 minutes or more, 24 hours or more, etc.). For example: `CarbonInterval::make('1 hour and 67 minutes')->forHumans(['parts' => 1])` returns `"1 hour"` while `CarbonInterval::make('1 hour and 67 minutes')->forHumans(['parts' => 1, 'options' => Carbon::FLOOR])` returns `"2 hours"`.
*   `Carbon::NO_ZERO_DIFF` (enabled by default): turns empty diff into 1 second
*   `Carbon::JUST_NOW` disabled by default): turns diff from now to now into "just now"
*   `Carbon::ONE_DAY_WORDS` (disabled by default): turns "1 day from now/ago" to "yesterday/tomorrow"
*   `Carbon::TWO_DAY_WORDS` (disabled by default): turns "2 days from now/ago" to "before yesterday/after
*   `Carbon::SEQUENTIAL_PARTS_ONLY` (disabled by default): will keep only the first sequence of units of the interval, for example if the diff would have been "2 weeks 1 day 34 minutes 12 seconds" as day and minute are not consecutive units, you will get: "2 weeks 1 day".

Use the pipe operator to enable/disable multiple option at once, example: `Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS`

You also can use `Carbon::enableHumanDiffOption($option)`, `Carbon::disableHumanDiffOption($option)`, `Carbon::setHumanDiffOptions($options)` to change the default options and `Carbon::getHumanDiffOptions()` to get default options but you should avoid using it as being static it may conflict with calls from other code parts/third-party libraries.

Aliases and reverse methods are provided for semantic purpose:

*   `from($other = null, $syntax = null, $short = false, $parts = 1, $options = null)` (alias of diffForHumans)
*   `since($other = null, $syntax = null, $short = false, $parts = 1, $options = null)` (alias of diffForHumans)
*   `to($other = null, $syntax = null, $short = false, $parts = 1, $options = null)` (inverse result, swap before and future diff)
*   `until($other = null, $syntax = null, $short = false, $parts = 1, $options = null)` (alias of to)
*   `fromNow($syntax = null, $short = false, $parts = 1, $options = null)` (alias of from with first argument omitted unless the first argument is a `DateTimeInterface`, now used instead), for semantic usage: produce an "3 hours from now"-like string with dates in the future
*   `ago($syntax = null, $short = false, $parts = 1, $options = null)` (alias of fromNow), for semantic usage: produce an "3 hours ago"-like string with dates in the past
*   `toNow($syntax = null, $short = false, $parts = 1, $options = null)` (alias of to with first argument omitted, now used instead)
*   `timespan($other = null, $timezone = null)` calls diffForHumans with options `join = ', '` (coma-separated), `syntax = CarbonInterface::DIFF_ABSOLUTE` (no "ago"/"from now"/"before"/"after" wording), `options = CarbonInterface::NO_ZERO_DIFF` (no "just now"/"yesterday"/"tomorrow" wording), `parts = -1` (no limits) In this mode, you can't change options but you can pass an optional date to compare with or a string + timezone to parse to get this date.

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

# Constants

The following constants are defined in the Carbon class.

```php
// These getters specifically return integers, ie intval()
{{::exec(var_dump(Carbon::SUNDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::MONDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::TUESDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::WEDNESDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::THURSDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::FRIDAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::SATURDAY);/*pad(50)*/)}} // {{eval}}

{{::exec(var_dump(Carbon::YEARS_PER_CENTURY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::YEARS_PER_DECADE);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::MONTHS_PER_YEAR);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::WEEKS_PER_YEAR);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::DAYS_PER_WEEK);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::HOURS_PER_DAY);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::MINUTES_PER_HOUR);/*pad(50)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::SECONDS_PER_MINUTE);/*pad(50)*/)}} // {{eval}}

```

```php
{{::lint(
$dt = Carbon::createFromDate(2012, 10, 6);
if ($dt->dayOfWeek === Carbon::SATURDAY) {
    echo 'Place bets on Ottawa Senators Winning!';
}
)}}

```

# Serialization

The Carbon instances can be serialized (including CarbonImmutable, CarbonInterval and CarbonPeriod).

```php
{{::lint(
$dt = Carbon::create(2012, 12, 25, 20, 30, 00, 'Europe/Moscow');
)}}

{{::exec(echo serialize($dt);/*pad(65)*/)}} // {{eval}}
// same as:
{{::exec(echo $dt->serialize();/*pad(65)*/)}} // {{eval}}

{{::lint(
$dt = 'O:13:"Carbon\Carbon":3:{s:4:"date";s:26:"2012-12-25 20:30:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}';
)}}

{{::exec(echo unserialize($dt)->format('Y-m-d\TH:i:s.uP T');/*pad(65)*/)}} // {{eval}}
// same as:
{{::exec(echo Carbon::fromSerialized($dt)->format('Y-m-d\TH:i:s.uP T');/*pad(65)*/)}} // {{eval}}

// you can pass options to Carbon::fromSerialized the same way as you can with unserialize
{{::exec(echo Carbon::fromSerialized(
    $dt,
    ['allowed_classes' => [Carbon::class]],
)->format('Y-m-d\TH:i:s.uP T');)}} // {{eval}}

```

# JSON

The Carbon instances can be encoded to and decoded from JSON. Since the version 2.4, we explicitly require the PHP JSON extension. It should have no impact as this extension is bundled by default with PHP. If the extension is disabled, be aware you will be locked on 2.3. But you still can use `--ignore-platform-reqs` on composer update/install to upgrade then polyfill the missing `JsonSerializable` interface by including [JsonSerializable.php](https://github.com/briannesbitt/Carbon/blob/2.3.0/src/JsonSerializable.php).

```php
{{::lint(
$dt = Carbon::create(2012, 12, 25, 20, 30, 00, 'Europe/Moscow');
)}}
{{::exec(echo json_encode($dt);)}}
// {{eval}}

{{::lint(
$json = '{"date":"2012-12-25 20:30:00.000000","timezone_type":3,"timezone":"Europe\/Moscow"}';
$dt = Carbon::__set_state(json_decode($json, true));
)}}
{{::exec(echo $dt->format('Y-m-d\TH:i:s.uP T');)}}
// {{eval}}

```

You can use `settings(['toJsonFormat' => $format])` to customize the serialization.

```php
{{::lint(
$dt = Carbon::create(2012, 12, 25, 20, 30, 00, 'Europe/Moscow')->settings([
    'toJsonFormat' => function ($date) {
        return $date->getTimestamp();
    },
]);
)}}
{{::exec(echo json_encode($dt);)}} // {{eval}}

```

If you want to apply this globally, first consider using factory, else or if you use Carbon 1 you can use:

```php
{{::lint(
$dt = Carbon::create(2012, 12, 25, 20, 30, 00, 'Europe/Moscow');
Carbon::serializeUsing(function ($date) {
    return $date->valueOf();
});
)}}
{{::exec(echo json_encode($dt);)}} // {{eval}}

// Call serializeUsing with null to reset the serializer:
{{::lint(
Carbon::serializeUsing(null);
)}}

```

The `jsonSerialize()` method allows you to call the function given to `Carbon::serializeUsing()` or the result of `toJson()` if no custom serialization specified.

# Macro

You may be familiar with the macro concept if you are used to working with Laravel and objects such as [response](https://laravel.com/docs/responses#response-macros) or [collections](https://laravel.com/docs/collections#extending-collections). Carbon macros works just like the Laravel `Macroable` Trait.

Call the `Carbon::macro()` method with the name of your macro as the first argument and a closure as the second argument. This will make the closure action available on all Carbon instances.

```php
{{::lint(
Carbon::macro('diffFromYear', static function ($year, $absolute = false, $short = false, $parts = 1) {
    return self::this()->diffForHumans(Carbon::create($year, 1, 1, 0, 0, 0), $absolute, $short, $parts);
});
)}}

// Can be called on Carbon instances:
//    self::context() = current instance ($this) or null when called statically
//    self::this() = current instance ($this) or Carbon::now() when called statically
{{::exec(echo Carbon::parse('2020-01-12 12:00:00')->diffFromYear(2019);/*pad(78)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::parse('2020-01-12 12:00:00')->diffFromYear(2019, true);/*pad(78)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::parse('2020-01-12 12:00:00')->diffFromYear(2019, true, true);/*pad(78)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::parse('2020-01-12 12:00:00')->diffFromYear(2019, true, true, 5);/*pad(78)*/)}} // {{eval}}

// Can also be called statically, in this case self::this() = Carbon::now()
echo "\n";
{{::exec(echo Carbon::diffFromYear(2017);/*pad(78)*/)}} // {{eval}}

```

Note that the closure is preceded by `static` and uses `self::this()` (available since version 2.25.0) instead of `$this`. This is the standard way to create Carbon macros, and this also applies to macros on other classes (`CarbonImmutable`, `CarbonInterval` and `CarbonPeriod`).

By following this pattern you ensure other developers of you team (and future you) can rely safely on the assertion: `Carbon::anyMacro()` is equivalent to `Carbon::now()->anyMacro()`. This makes the usage of macros consistent and predictable and ensures developers that any macro can be called safely either statically or dynamically.

The sad part is IDE will not natively your macro method (no auto-completion for the method `diffFromYear` in the example above). But it's no longer a problem thanks to our CLI tool: [carbon-cli](https://github.com/kylekatarnls/carbon-cli) that allows you to generate IDE helper files for your mixins and macros.

Macros are the perfect tool to output dates with some settings or user preferences.

```php
{{::lint(
// Let assume you get user settings from the browser or preferences stored in a database
$userTimezone = 'Europe/Paris';
$userLanguage = 'fr_FR';

Carbon::macro('formatForUser', static function () use ($userTimezone, $userLanguage) {
    $date = self::this()->copy()->tz($userTimezone)->locale($userLanguage);

    return $date->calendar(); // or ->isoFormat($customFormat), ->diffForHumans(), etc.
});

// Then let assume you store all your dates/times in UTC (because you definitely should)
$dateString = '2010-01-23 10:00:00'; // Get this from your database or any input
)}}

// Then now you can easily display any date in a page/e-mail using those user settings and the chosen format
{{::exec(echo Carbon::parse($dateString, 'UTC')->formatForUser();/*pad(58)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::tomorrow()->formatForUser();/*pad(58)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->subDays(3)->formatForUser();/*pad(58)*/)}} // {{eval}}

```

Macros can also be grouped in classes and be applied with `mixin()`

```php
{{::lint(
class BeerDayCarbonMixin
{
    public function nextBeerDay()
    {
        return static function () {
            return self::this()->modify('next wednesday');
        };
    }

    public function previousBeerDay()
    {
        return static function () {
            return self::this()->modify('previous wednesday');
        };
    }
}

Carbon::mixin(new BeerDayCarbonMixin());

$date = Carbon::parse('First saturday of December 2018');
)}}

{{::exec(echo $date->previousBeerDay();/*pad(78)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->nextBeerDay();/*pad(78)*/)}} // {{eval}}

```

Since Carbon 2.23.0, it's also possible to shorten the mixin syntax using traits:

```php
{{::lint(
trait BeerDayCarbonTrait
{
    public function nextBeerDay()
    {
        return $this->modify('next wednesday');
    }

    public function previousBeerDay()
    {
        return $this->modify('previous wednesday');
    }
}

Carbon::mixin(BeerDayCarbonTrait::class);

$date = Carbon::parse('First saturday of December 2018');
)}}

{{::exec(echo $date->previousBeerDay();/*pad(78)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->nextBeerDay();/*pad(78)*/)}} // {{eval}}

```

You can check if a macro (mixin included) is available with `hasMacro()` and retrieve the macro closure with `getMacro()`

```php
{{::exec(var_dump(Carbon::hasMacro('previousBeerDay'));/*pad(78)*/)}} // {{eval}}
{{::exec(var_dump(Carbon::hasMacro('diffFromYear'));/*pad(78)*/)}} // {{eval}}
echo "\n";
{{::exec(var_dump(Carbon::hasMacro('dontKnowWhat'));/*pad(78)*/)}} // {{eval}}

```

A macro starting with `get` followed by an uppercase letter will automatically provide a dynamic getter whilst a macro starting with `set` and followed by an uppercase letter will provide a dynamic setter:

```php
{{::lint(// Let's say a school year starts 5 months before the start of the year, so the school year of 2018 actually begins in August 2017 and ends in July 2018,
// Then you can create get/set method this way:
Carbon::macro('setSchoolYear', static function ($schoolYear) {
    $date = self::this();
    $date->year = $schoolYear;

    if ($date->month > 7) {
        $date->year--;
    }
});
Carbon::macro('getSchoolYear', static function () {
    $date = self::this();
    $schoolYear = $date->year;

    if ($date->month > 7) {
        $schoolYear++;
    }

    return $schoolYear;
});
// This will make getSchoolYear/setSchoolYear as usual, but get/set prefix will also enable
// the getter and setter methods for the ->schoolYear property.

$date = Carbon::parse('2016-06-01');
)}}

{{::exec(var_dump($date->schoolYear);/*pad(46)*/)}} // {{eval}}
{{::lint($date->addMonths(3);)}}
{{::exec(var_dump($date->schoolYear);/*pad(46)*/)}} // {{eval}}
{{::lint($date->schoolYear++;)}}
{{::exec(var_dump($date->format('Y-m-d'));/*pad(46)*/)}} // {{eval}}
{{::lint($date->schoolYear = 2020;)}}
{{::exec(var_dump($date->format('Y-m-d'));/*pad(46)*/)}} // {{eval}}

```

You can also intercept any other call with generic macro:

```php
{{::lint(
Carbon::genericMacro(static function ($method) {
    // As an example we will convert firstMondayOfDecember into first Monday Of December to get strings that
    // DateTime can parse.
    $time = preg_replace('/[A-Z]/', ' $0', $method);

    try {
        return self::this()->modify($time);
    } catch (\Throwable $exception) {
        if (stripos($exception->getMessage(), 'Failed to parse') !== false) {
            // When throwing BadMethodCallException from inside a generic macro will go to next generic macro
            // if there are other registered.
            throw new \BadMethodCallException('Try next macro', 0, $exception);
        }

        // Other exceptions thrown will not be caught
        throw $exception;
    }
}, 1 /* you can optionally pass a priority as a second argument, 0 by default, can be negative, higher priority ran first */);
// Generic macro get the asked method name as first argument, and method arguments as others.
// They can return any value.
// They can be added via "genericMacros" setting and this setting has precedence over statically declared generic macros.

$date = Carbon::parse('2016-06-01');
)}}

{{::exec(echo $date->nextSunday();/*pad(42)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $date->lastMondayOfPreviousMonth();/*pad(42)*/)}} // {{eval}}
{{::lint(Carbon::resetMacros();)}} // resetMacros remove all macros and generic macro declared statically

```

And guess what? all macro methods are also available on [`CarbonInterval`](#api-interval) and [`CarbonPeriod`](#api-period) classes.

```php
{{::lint(
CarbonInterval::macro('twice', static function () {
    return self::this()->times(2);
});
)}}
{{::exec(echo CarbonInterval::day()->twice()->forHumans();/*pad(42)*/)}} // {{eval}}
{{::lint($interval = CarbonInterval::hours(2)->addMinutes(15)->twice();)}}
{{::exec(echo $interval->forHumans(['short' => true]);/*pad(42)*/)}} // {{eval}}

```

```php
{{::lint(
CarbonPeriod::macro('countWeekdays', static function () {
    return self::this()->filter('isWeekday')->count();
});
)}}
{{::exec(echo CarbonPeriod::create('2017-11-01', '2017-11-30')->countWeekdays();/*pad(72)*/)}} // {{eval}}
{{::exec(echo CarbonPeriod::create('2017-12-01', '2017-12-31')->countWeekdays();/*pad(72)*/)}} // {{eval}}

```

We provide a PHPStan extension out of the box you can include in your **phpstan.neon**:

```yaml
includes:
    - vendor/nesbot/carbon/extension.neon
parameters:
    bootstrapFiles:
        # You will also need to add here the file
        # that enable your macros and mixins:
        - config/bootstrap.php
```

If you're using Laravel, you can consider using [larastan](https://github.com/nunomaduro/larastan) which provides a complete support of Laravel features in PHPStan (including Carbon macros). Alternatively to include the neon file, you can install **phpstan/extension-installer**:

```
composer require --dev phpstan/phpstan phpstan/extension-installer
```

Then add the file where your Carbon macros and mixins are defined in the **bootstrapFiles** entry:

```yaml
parameters:
    bootstrapFiles:
        - config/bootstrap.php
```

**📦 Carbon extension libraries**

Check [cmixin/business-time](https://github.com/kylekatarnls/business-time) (that includes [cmixin/business-day](https://github.com/kylekatarnls/business-day)) to handle both holidays and business opening hours with a lot of advanced features.

Check [cmixin/season](https://github.com/kylekatarnls/season) to check if date is in a given season, which season of the year is a date, or when season starts and ends.

Check [cmixin/enhanced-period](https://github.com/kylekatarnls/enhanced-period) for more period features: \`touchesWith()\`, \`overlapsWith()\`, \`duration()\`, \`diff()\`, \`diffAny()\`, etc.

Check [Carbonite](https://github.com/kylekatarnls/carbonite) for more advanced Carbon testing features.

**🙌 More macros and mixins below shared by users**

```php
{{::lint(
class CurrentDaysCarbonMixin
{
    /**
     * Get the all dates of week
     *
     * @return array
     */
    public static function getCurrentWeekDays()
    {
        return static function () {
            $startOfWeek = self::this()->startOfWeek()->subDay();
            $weekDays = [];

            for ($i = 0; $i < static::DAYS_PER_WEEK; $i++) {
                $weekDays[] = $startOfWeek->addDay()->startOfDay()->copy();
            }

            return $weekDays;
        };
    }

    /**
     * Get the all dates of month
     *
     * @return array
     */
    public static function getCurrentMonthDays()
    {
        return static function () {
            $date = self::this();
            $startOfMonth = $date->copy()->startOfMonth()->subDay();
            $endOfMonth = $date->copy()->endOfMonth()->format('d');
            $monthDays = [];

            for ($i = 0; $i < $endOfMonth; $i++)
            {
                $monthDays[] = $startOfMonth->addDay()->startOfDay()->copy();
            }

            return $monthDays;
        };
    }
}

Carbon::mixin(new CurrentDaysCarbonMixin());

function dumpDateList($dates) {
    echo substr(implode(', ', $dates), 0, 100).'...';
}
)}}

{{::exec(dumpDateList(Carbon::getCurrentWeekDays());/*pad(65)*/)}} // {{eval}}
{{::exec(dumpDateList(Carbon::getCurrentMonthDays());/*pad(65)*/)}} // {{eval}}
{{::exec(dumpDateList(Carbon::now()->subMonth()->getCurrentWeekDays());/*pad(65)*/)}} // {{eval}}
{{::exec(dumpDateList(Carbon::now()->subMonth()->getCurrentMonthDays());/*pad(65)*/)}} // {{eval}}

```

_Credit: [meteguerlek](https://github.com/meteguerlek) ([#1191](https://github.com/briannesbitt/Carbon/pull/1191))._

```php
{{::lint(
Carbon::macro('toAtomStringWithNoTimezone', static function () {
    return self::this()->format('Y-m-d\TH:i:s');
});
)}}
{{::exec(echo Carbon::parse('2021-06-16 20:08:34')->toAtomStringWithNoTimezone();)}} // {{eval}}

```

_Credit: [afrojuju1](https://github.com/afrojuju1) ([#1063](https://github.com/briannesbitt/Carbon/pull/1063))._

```php
{{::lint(
Carbon::macro('easterDate', static function ($year) {
    return Carbon::createMidnightDate($year, 3, 21)->addDays(easter_days($year));
});
)}}
{{::exec(echo Carbon::easterDate(2015)->format('d/m');)}} // {{eval}}
{{::exec(echo Carbon::easterDate(2016)->format('d/m');)}} // {{eval}}
{{::exec(echo Carbon::easterDate(2017)->format('d/m');)}} // {{eval}}
{{::exec(echo Carbon::easterDate(2018)->format('d/m');)}} // {{eval}}
{{::exec(echo Carbon::easterDate(2019)->format('d/m');)}} // {{eval}}

```

_Credit: [andreisena](https://github.com/andreisena), [36864](https://github.com/36864) ([#1052](https://github.com/briannesbitt/Carbon/pull/1052))._

Check [cmixin/business-day](https://github.com/kylekatarnls/business-day) for a more complete holidays handler.

```php
{{::lint(
Carbon::macro('datePeriod', static function ($startDate, $endDate) {
    return new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate);
});
)}}
{{::exec(foreach (Carbon::datePeriod(Carbon::createMidnightDate(2019, 3, 28), Carbon::createMidnightDate(2019, 4, 3)) as $date) {
    echo $date->format('Y-m-d') . "\n";
})}}
/*
{{eval}}*/

```

_Credit: [reinink](https://github.com/reinink) ([#132](https://github.com/briannesbitt/Carbon/pull/132))._

```php
{{::lint(
Carbon::macro('formatBuddhist', static function (string $format): string {
    $self = self::this();

    $format = strtr($format, [
        'o' => '[{1}]',
        'Y' => '[{2}]',
        'y' => '[{3}]',
    ]);

    $function = $self->localFormatFunction ?: static::$formatFunction;

    if (!$function) {
        $format = $self->rawFormat($format);
    } else if (\is_string($function) && method_exists($self, $function)) {
        $format = [$self, $function];
        $format = $function(...\func_get_args());
    }

    $buddhistYear = $self->year + 543;

    return strtr($format, [
        '[{1}]' => $self->format('o') + 543,
        '[{2}]' => $buddhistYear,
        '[{3}]' => str_pad($buddhistYear % 100, 2, '0', STR_PAD_LEFT),
    ]);
});
)}}
{{::exec(echo Carbon::parse('2024-02-29 10.55.32')->formatBuddhist('Y-m-d H:i:s');)}} // {{eval}}
{{::exec(echo Carbon::parse('2024-02-29 10.55.32')->formatBuddhist('d/m/y');)}}       // {{eval}}

```

_From on original idea of: [mean-cj](https://github.com/mean-cj) ([#2954](https://github.com/briannesbitt/Carbon/issues/2954))._

```php
{{::lint(
class UserTimezoneCarbonMixin
{
    public $userTimeZone;

    /**
     * Set user timezone, will be used before format function to apply current user timezone
     *
     * @param $timezone
     */
    public function setUserTimezone()
    {
        $mixin = $this;

        return static function ($timezone) use ($mixin) {
            $mixin->userTimeZone = $timezone;
        };
    }

    /**
     * Returns date formatted according to given format.
     *
     * @param string $format
     *
     * @return string
     *
     * @link https://www.php.net/manual/en/datetime.format.php
     */
    public function tzFormat()
    {
        $mixin = $this;

        return static function ($format) use ($mixin) {
            $date = self::this();

            if (!is_null($mixin->userTimeZone)) {
                $date->timezone($mixin->userTimeZone);
            }

            return $date->format($format);
        };
    }
}

Carbon::mixin(new UserTimezoneCarbonMixin());

Carbon::setUserTimezone('Europe/Berlin');
)}}
{{::exec(echo Carbon::createFromTime(12, 0, 0, 'UTC')->tzFormat('H:i');)}} // {{eval}}
{{::exec(echo Carbon::createFromTime(15, 0, 0, 'UTC')->tzFormat('H:i');)}} // {{eval}}
{{::lint(
Carbon::setUserTimezone('America/Toronto');
)}}
{{::exec(echo Carbon::createFromTime(12, 0, 0, 'UTC')->tzFormat('H:i');)}} // {{eval}}
{{::exec(echo Carbon::createFromTime(15, 0, 0, 'UTC')->tzFormat('H:i');)}} // {{eval}}

```

_Credit: [thiagocordeiro](https://github.com/thiagocordeiro) ([#927](https://github.com/briannesbitt/Carbon/pull/927))._

Whilst using a macro is the recommended way to add new methods or behaviour to Carbon, you can go further and extend the class itself which allows some alternative ways to override the primary methods; parse, format and createFromFormat.

```php
{{::lint(
class MyDateClass extends Carbon
{
    protected static $formatFunction = 'translatedFormat';

    protected static $createFromFormatFunction = 'createFromLocaleFormat';

    protected static $parseFunction = 'myCustomParse';

    public static function myCustomParse($string)
    {
        return static::rawCreateFromFormat('d m Y', $string);
    }
}

$date = MyDateClass::parse('20 12 2001')->locale('de');

)}}
{{::exec(echo $date->format('jS F y');/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($date = MyDateClass::createFromFormat('j F Y', 'pt', '20 fevereiro 2001')->locale('pt');)}}

{{::exec(echo $date->format('d/m/Y');/*pad(40)*/)}} // {{eval}}
echo "\n";

// Note than you can still access native methods using rawParse, rawFormat and rawCreateFromFormat:
{{::lint($date = MyDateClass::rawCreateFromFormat('j F Y', '20 February 2001', 'UTC')->locale('pt');)}}

{{::exec(echo $date->rawFormat('jS F y');/*pad(40)*/)}} // {{eval}}
echo "\n";

{{::lint($date = MyDateClass::rawParse('2001-02-01', 'UTC')->locale('pt');)}}

{{::exec(echo $date->format('jS F y');/*pad(40)*/)}} // {{eval}}
echo "\n";

```

The following macro allow you to choose a timezone using only the city name (omitting continent). Perfect to make your unit tests more fluent:

```php
{{::lint(Carbon::macro('goTo', function (string $city) {
    static $cities = null;

    if ($cities === null) {
        foreach (\DateTimeZone::listIdentifiers() as $identifier) {
            $chunks = explode('/', $identifier);

            if (isset($chunks[1])) {
                $id = strtolower(end($chunks));
                $cities[$id] = $identifier;
            }
        }
    }

    $city = str_replace(' ', '_', strtolower($city));

    if (!isset($cities[$city])) {
        throw new \InvalidArgumentException("$city not found.");
    }

    return $this->tz($cities[$city]);
});)}}

{{::exec(echo Carbon::now()->goTo('Chicago')->tzName;/*pad(40)*/)}} // {{eval}}
echo "\n";
{{::exec(echo Carbon::now()->goTo('Buenos Aires')->tzName;/*pad(40)*/)}} // {{eval}}

```

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

# CarbonTimeZone

Starting with Carbon 2, timezones are now handled with a dedicated class `CarbonTimeZone` extending [DateTimeZone](https://www.php.net/manual/en/class.datetimezone.php).

```php
{{::lint(
$tz = new CarbonTimeZone('Europe/Zurich'); // instance way
$tz = CarbonTimeZone::create('Europe/Zurich'); // static way
)}}

// Get the original name of the timezone (can be region name or offset string):
{{::exec(echo $tz->getName();/*pad(36)*/)}} // {{eval}}
echo "\n";
// Casting a CarbonTimeZone to string will automatically call getName:
{{::exec(echo $tz;/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $tz->getAbbreviatedName();/*pad(36)*/)}} // {{eval}}
echo "\n";
// With DST on:
{{::exec(echo $tz->getAbbreviatedName(true);/*pad(36)*/)}} // {{eval}}
echo "\n";
// Alias of getAbbreviatedName:
{{::exec(echo $tz->getAbbr();/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $tz->getAbbr(true);/*pad(36)*/)}} // {{eval}}
echo "\n";
// toRegionName returns the first matching region or false, if timezone was created with a region name,
// it will simply return this initial value.
{{::exec(echo $tz->toRegionName();/*pad(36)*/)}} // {{eval}}
echo "\n";
// toOffsetName will give the current offset string for this timezone:
{{::exec(echo $tz->toOffsetName();/*pad(36)*/)}} // {{eval}}
echo "\n";
// As with DST, this offset can change depending on the date, you may pass a date argument to specify it:
{{::lint($winter = Carbon::parse('2018-01-01');)}}
{{::exec(echo $tz->toOffsetName($winter);/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::lint($summer = Carbon::parse('2018-07-01');)}}
{{::exec(echo $tz->toOffsetName($summer);/*pad(36)*/)}} // {{eval}}

```

The default timezone is given by [date\_default\_timezone\_get](https://www.php.net/manual/en/function.date-default-timezone-get.php) so it will be driven by the INI settings [date.timezone](https://www.php.net/manual/en/datetime.configuration.php#ini.date.timezone) but you really should override it at application level using [date\_default\_timezone\_set](https://www.php.net/manual/en/function.date-default-timezone-set.php) and you should set it to `"UTC"`, if you're temped to or already use an other timezone as default, please read the following article: [Always Use UTC Dates And Times](https://medium.com/@kylekatarnls/always-use-utc-dates-and-times-8a8200ca3164).

It explains why UTC is a reliable standard. And this best-practice is even more important in PHP because the PHP DateTime API has many bugs with offsets changes and DST timezones. Some of them appeared on minor versions and even on patch versions (so you can get different results running the same code on PHP 7.1.7 and 7.1.8 for example) and some bugs are not even fixed yet. So we highly recommend to use UTC everywhere and only change the timezone when you want to display a date. See our [first macro example](#user-settings).

While, region timezone ("Continent/City") can have DST and so have variable offset during the year, offset timezone have constant fixed offset:

```php
{{::lint(
$tz = CarbonTimeZone::create('+03:00'); // full string
$tz = CarbonTimeZone::create(3); // or hour integer short way

$tz = CarbonTimeZone::createFromHourOffset(3); // explicit method rather type-based detection is even better
$tz = CarbonTimeZone::createFromMinuteOffset(180); // the equivalent in minute unit

// Both above rely on the static minute-to-string offset converter also available as:
$tzString = CarbonTimeZone::getOffsetNameFromMinuteOffset(180);
$tz = CarbonTimeZone::create($tzString);
)}}

{{::exec(echo $tz->getName();/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $tz;/*pad(36)*/)}} // {{eval}}
echo "\n";
// toRegionName will try to guess what region it could be:
{{::exec(echo $tz->toRegionName();/*pad(36)*/)}} // {{eval}}
echo "\n";
// to guess with DST off:
{{::exec(echo $tz->toRegionName(null, 0);/*pad(36)*/)}} // {{eval}}
echo "\n";
// toOffsetName will give the initial offset no matter the date:
{{::exec(echo $tz->toOffsetName();/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $tz->toOffsetName($winter);/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::exec(echo $tz->toOffsetName($summer);/*pad(36)*/)}} // {{eval}}

```

You also can convert region timezones to offset timezones and reciprocally.

```php
{{::lint(
$tz = new CarbonTimeZone(7);
)}}

{{::exec(echo $tz;/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::lint($tz = $tz->toRegionTimeZone();)}}
{{::exec(echo $tz;/*pad(36)*/)}} // {{eval}}
echo "\n";
{{::lint($tz = $tz->toOffsetTimeZone();)}}
{{::exec(echo $tz;/*pad(36)*/)}} // {{eval}}

```

You can create a `CarbonTimeZone` from mixed values using `instance()` method.

```php
{{::lint(
$tz = CarbonTimeZone::instance(new \DateTimeZone('Europe/Paris'));
)}}

{{::exec(echo $tz;/*pad(36)*/)}} // {{eval}}
echo "\n";

// Bad timezone will throw an exception
{{::lint(
try {
    CarbonTimeZone::instance('Europe/Chicago');
} catch (\InvalidArgumentException $exception) {
    $error = $exception->getMessage();
}
)}}
{{::exec(echo $error;/*pad(36)*/)}} // {{eval}}

// as some value cannot be dump as string in an error message or
// have unclear dump, you may pass a second argument to display
// instead in the errors
{{::lint(
try {
    $continent = 'Europe';
    $city = 'Chicago';
    $mixedValue = ['continent' => $continent, 'city' => $city];
    CarbonTimeZone::instance("$continent/$city", json_encode($mixedValue));
} catch (\InvalidArgumentException $exception) {
    $error = $exception->getMessage();
}
)}}
{{::exec(echo $error;/*pad(36)*/)}} // {{eval}}

```

The same way, `Carbon::create()` return `false` if you pass an incorrect value (such as a negative month) but it throws an exception in strict mode. `Carbon::createStrict()` is like `create()` but throws an exception even if not in strict mode.

# Migrate to Carbon 3

If you plan to migrate from Carbon 2 to Carbon 3, please note the following breaking changes you should take care of.

## createFromTimestamp UTC by default

Timezone for `createFromTimestamp` was previously `date_default_timezone_get()` in Carbon 2.

In Carbon 3, it's now `"UTC"` so to be consistent with `DateTime` behavior when doing `new DateTime('@123')`.

Those who use UTC as default timezone will not be impacted. As a reminder we strongly recommend this setup. See [timezone section](#api-timezone)

We also recommend to always call `createFromTimestamp` with 2 parameters (i.e. explicitly pass a timezone) or to use `createFromTimestampUTC()`.

## diffIn\*

Yes, the most impactful change is in `diffIn*` methods. They were returning positive integer by default, they will now return float:

```php
{{::lint(
$after = Carbon::now()->addSeconds(2);
$before = Carbon::now();

var_dump($after->diffInSeconds($before));
)}}
```

This was: `int(1)` in Carbon 2

This is: `double(-1.999508)` in Carbon 3

In Carbon 3, using `(int) $after->diffInSeconds($before, true)` or `(int) abs($after->diffInSeconds($before))` allows to get explicitly an absolute and truncated value, so the same result as in v2.

## Strong typing

Strong typing added in many method parameters will disallow so usages that was never meant to be supported such as `bool` or `null` in comparison methods.

In Carbon v2 the following return arbitrary results (despite it emits `E_USER_DEPRECATED` notices):

```php
var_dump($date->eq(false)); // false
var_dump($date->gte(false)); // true
var_dump($date->gt(false)); // true
var_dump($date->eq(null)); // false
var_dump($date->gte(null)); // true
var_dump($date->gt(null)); // true
```

In Carbon v3, PHPDoc is moving to real types so any of this will throw a `TypeError`, it expects `DateTimeInterface` or `string` to be passed.

## falsable => nullable

`create`, `createFromFormat` , `createFromIsoFormat`, `createFromLocaleFormat` and `createFromLocaleIsoFormat` returned `Carbon|false`, it's now `Carbon|null` (unlocking `?->` chaining and `?? throw` error handling).

## Removal of some deprecated methods

`setUtf8` are `formatLocalized` removed. `formatLocalized` depends on OS language package and relies on `setlocale()` + `strftime()` (the last will be removed from PHP itself). `->isoFormat()` should be used instead which is embedded translation files (so one can rely on getting the same translation everywhere) and is linked to Carbon locale (The current object locale that can be set with `$date->locale('de')` or if not set, the global one that Laravel is already setting using `Carbon::setLocale('de')`). And `setUtf8` is no longer needed since `formatLocalized` is the only method that possibly had non-UTF-8 output.

`setWeekStartsAt` and `setWeekEndsAt` are also removed. You should either specify the day explictly when calling `startOfWeek`, for instance: `->startOfWeek(\Carbon\WeekDay::Wednesday)` or change the locale appriopriately, for instance `->locale('en_US')->startOfWeek()` goes to Sunday, while `->locale('en_GB')->startOfWeek()` goes to Monday, `ar_EG` locale sets it to Saturday, etc.

Note that you can also create custom locales with a different start of week:

```php
{{::lint(
\Carbon\Translator::get('en_US@Cinema')->setTranslations([
    'first_day_of_week' => Carbon::FRIDAY,
]);

\Carbon\Translator::get('fr_FR@Cinema')->setTranslations([
    'first_day_of_week' => Carbon::WEDNESDAY,
]);)}}

{{::exec(echo CarbonImmutable::now()->locale('fr_FR')->startOfWeek()->dayName;/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonImmutable::now()->locale('fr_FR@Cinema')->startOfWeek()->dayName;/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonImmutable::now()->locale('en_US')->startOfWeek()->dayName;/*pad(76)*/)}} // {{eval}}
{{::exec(echo CarbonImmutable::now()->locale('en_US@Cinema')->startOfWeek()->dayName;/*pad(76)*/)}} // {{eval}}

```

## Strict timezone

All named argument `tz:` have been replaced with `timezone:` for explicitness and consistency with `DateTime` methods.

It's no longer possible to create a `CarbonTimeZone` object with 0 arguments, the name is now mandatory to construct it.

Creating timezone with a bad/unknown name will now always throw an exception, it's no longer possible to mute them by disabling strict-mode.

## Immutable start and end properties of CarbonPeriod

`CarbonPeriod` now extends `DatePeriod` so it can now conveniently substitute it anywhere a `DatePeriod` is expected. However, it also inherits the constraint that `->start` and `->end` are immutable properties. We recommend that you use `CarbonPeriodImmutable` which will then be fully compatible (its properties including start and end cannot be modified); while `CarbonPeriod` has setters `->setStartDate()` and `->setEndDate()` that will actually modify from when to when the period iterates and also the value you can retrieve using `->getStartDate()` and `->getEndDate()`, however `->start` and `->end` will still contain initial start and end and not the new values after modification.

## isSame\*

Since Carbon 3, it's no longer allowed to call `isSame*` methods (such as `isSameDay`, `isSameMonth` without parameters. You need to explictly pass a date (string or DateTime object) so it's a comparison between 2: `$a->isSameHour($b)`, in Carbon 2, it was by default using "now" as default value, now you should tell it explictly if you mean it: `$a->isSameHour('now')` or you can use `->isCurrent*` methods instead: `$a->isCurrentHour()`

## Dropped maxValue() and minValue()

Since Carbon 3, `Carbon::minValue()` and `Carbon::maxValue()` have been removed, use `CarbonImmutable::startOfTime()` and `CarbonImmutable::endOfTime()` instead. While the old methods depended on the system and were available on mutable objects, the new ones are arbitrary dates `0001-01-01 00:00:00` and `9999-12-31 23:59:59.999999` only available on `CarbonImmutable`, and you can check a date is at such end using `->isStartOfTime()` and `->isEndOfTime()`.

# Migrate to Carbon 2

If you plan to migrate from Carbon 1 to Carbon 2, please note the following breaking changes you should take care of.

*   Default values (when parameters are omitted) for `$month` and `$day` in the `::create()` method are now `1` (were values from current date in Carbon 1). And default values for `$hour`, `$minute` and `$second` are now `0`, this goes for omitted values, but you still can pass explicitly `null` to get the current value from _now_ (similar behavior as in Carbon 1).
*   Now you get microsecond precision everywhere, it also means 2 dates in the same second but not in the same microsecond are no longer equal.
*   `$date->jsonSerialize()` and `json_encode($date)` no longer returns arrays but simple strings: `"2017-06-27T13:14:15.000000Z"`. This allows to create dates from it easier in JavaScript. You still can get the previous behavior using:
    
    ```php
    {{::lint(
    Carbon::serializeUsing(function ($date) {
        return [
            'date' => $date->toDateTimeString(),
        ] + (array) $date->tz;
    });
    )}}
    ```
    
*   `$date->setToStringFormat()` with a closure no longer return a format but a final string. So you can return any string and the following in Carbon 1:
    
    ```php
    {{::lint(
    Carbon::setToStringFormat(function ($date) {
        return $date->year === 1976 ?
            'jS \o\f F g:i:s a' :
            'jS \o\f F, Y g:i:s a';
    });
    )}}
    ```
    
    would become in Carbon 2:
    
    ```php
    {{::lint(
    Carbon::setToStringFormat(function ($date) {
        return $date->formatLocalized($date->year === 1976 ?
            'jS \o\f F g:i:s a' :
            'jS \o\f F, Y g:i:s a'
        );
    });
    )}}
    ```
    
*   `setWeekStartsAt` and `setWeekEndsAt` no longer throw exceptions on values out of ranges, but they are also deprecated.
*   `isSameMonth` and `isCurrentMonth` now returns `false` for same month in different year but you can pass `false` as a second parameter of `isSameMonth` or first parameter of `isCurrentMonth` to compare ignoring the year.
*   `::compareYearWithMonth()` and `::compareYearWithMonth()` have been removed. Strict comparisons are now the default. And you can use the next parameter of isSame/isCurrent set to false to get month-only comparisons.
*   As we dropped PHP 5, `$self` is no longer needed in mixins you should just use `$this` instead.
*   As PHP 7.1+ perfectly supports microseconds, `useMicrosecondsFallback` and `isMicrosecondsFallbackEnabled` are no longer needed and so have been removed.
*   In Carbon 1, calls of an unknown method on `CarbonInterval` (ex: `CarbonInterval::anything()`) just returned null. In Carbon 2 they throw an exception.
*   In Carbon 1, `dayOfYear` started from `0`. In Carbon 2 it starts from `1`.
*   In Carbon 1, `null` was considered as `0` when passed to add/sub method (such as `addDays(null)`, `subMonths(null)`, etc.). In Carbon 2, it behaves the same as no parameters so default to `1`. Anyway, you're discouraged to pass `null` in such methods as it's ambiguous and the behavior for next major version is not guaranteed.
*   That's all folks! Every other methods you used in Carbon 1 should continue to work just the same with Carbon 2.

*   [Introduction](#api-introduction)
*   [Instantiation](#api-instantiation)
*   [Localization](#api-localization)
*   [Testing Aids](#api-testing)
*   [Getters](#api-getters)
*   [Setters](#api-setters)
*   [Weeks](#api-week)
*   [Fluent Setters](#api-settersfluent)
*   [String Formatting](#api-formatting)
*   [Common Formats](#api-commonformats)
*   [Conversion](#api-conversion)
*   [Comparison](#api-comparison)
*   [Addition and Subtraction](#api-addsub)
*   [Difference](#api-difference)
*   [Difference for Humans](#api-humandiff)
*   [Modifiers](#api-modifiers)
*   [Constants](#api-constants)
*   [Serialization](#api-serialization)
*   [JSON](#api-json)
*   [Macro](#api-macro)
*   [CarbonInterval](#api-interval)
*   [CarbonPeriod](#api-period)
*   [CarbonTimeZone](#api-timezone)
*   [Migrate to Carbon 3](#api-carbon-3)
*   [Migrate to Carbon 2](#api-carbon-2)