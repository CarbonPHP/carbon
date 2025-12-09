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
