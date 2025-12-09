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
