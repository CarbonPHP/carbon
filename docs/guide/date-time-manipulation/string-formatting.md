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
