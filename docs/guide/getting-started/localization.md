---
order: 3
---

# Localization

With Carbon 2, localization changed a lot, <span v-pre>{{eval(echo count(Carbon::getAvailableLocales()) - 73;)}}</span> new locales are supported, and we now embed locale formats, day names, month names, ordinal suffixes, meridiem, week start and more. While Carbon 1 provided partial support and relied on third-party like IntlDateFormatter class and language packages for advanced translation, you now benefit of a wide internationalization support. You still use Carbon 1? I hope you would consider to upgrade, version 2 has really cool new features. Otherwise, you can find the [version 1 documentation of Localization by clicking here](#localization-v1).

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

Here is the complete list of available replacements (examples given with `$date = Carbon::parse('2017-01-05 17:04:05.084512');)`):
<!--@include: @/parts(fix-path)/localization/replacements.md-->

Some macro-formats are also available. Here are examples of each in some languages:
<!--@include: @/parts(fix-path)/localization/macro-format.md-->


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

[Click here](#supported-locales) to get an overview of the <span v-pre>{{eval(echo count(Carbon::getAvailableMacroLocales());)}}</span> locales (and <span v-pre>{{eval(echo count(Carbon::getAvailableLocales());)}}</span> regional variants) supported by the last Carbon version:
<!--@include: @/parts(fix-code)/localization/supported-locales.md-->

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
