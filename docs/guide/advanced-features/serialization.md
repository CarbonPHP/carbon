# Serialization

The Carbon instances can be serialized (including CarbonImmutable, CarbonInterval and CarbonPeriod).

```php
$dt = Carbon::create(2012, 12, 25, 20, 30, 00, 'Europe/Moscow');

echo serialize($dt);
// same as:
echo $dt->serialize();

$dt = 'O:13:"Carbon\Carbon":3:{s:4:"date";s:26:"2012-12-25 20:30:00.000000";s:13:"timezone_type";i:3;s:8:"timezone";s:13:"Europe/Moscow";}';

echo unserialize($dt)->format('Y-m-d\TH:i:s.uP T');
// same as:
echo Carbon::fromSerialized($dt)->format('Y-m-d\TH:i:s.uP T');

// you can pass options to Carbon::fromSerialized the same way as you can with unserialize
echo Carbon::fromSerialized(
    $dt,
    ['allowed_classes' => [Carbon::class]],
)->format('Y-m-d\TH:i:s.uP T');
```

::: warning
⚠️ Warning! It's not safe to `unserialize()` a string coming from an untrusted source, the `Carbon` objects (`Carbon`,
`CarbonInterval`, `CarbonPeriod`, etc.) can be customized via their local settings in many ways (custom formats,
custom filters on the periods), hence when building those objects from a serialized string, it can have any values
(unvalidated) and even behavioral customizations.
:::

::: tip Info
ℹ️ When exchanging data with a foreign system, always prefer agnostic format such as JSON that will not trigger
any unexpected side effect when you unserialize it, then proceed to relevant validation before building PHP objects
from this data.

ℹ️ When you `unserialize()` a string, verify if this string could have been modified by any unauthorized
entity, for instance if it comes from a file, verify what else could modify the same file. This includes
where this string is stored but also all the places by which it transited.

ℹ️ If you need to `unserialize()` a string that come from a trusted source (your own application or a foreign
system considered safe) but that has transited by an untrusted intermediate to arrive back to your application,
then consider signing this string (with [openssl_sign](https://www.php.net/manual/en/function.openssl-sign.php)
for example) on the serializer end (the system that does the `serialize()` operation) with a private key only
known by this serializer part; then you can verify this signature (with
[openssl_verify](https://www.php.net/manual/en/function.openssl-verify.php) for example) to be sure it was
indeed produced by the expected serializer and has not been altered by intermediates.
:::
