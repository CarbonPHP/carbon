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
