<?php

declare(strict_types=1);

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carbon;

use Closure;
use DateTimeInterface;
use DateTimeZone;
use Symfony\Component\Clock\ClockInterface;
use Symfony\Component\Clock\NativeClock;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * A factory to generate CarbonImmutable instances with common settings.
 *
 * <autodoc generated by `composer phpdoc`>
 *
 * @method bool                canBeCreatedFromFormat(?string $date, string $format)                                                                          Checks if the (date)time string is in a given format and valid to create a
 *                                                                                                                                                            new instance.
 * @method ?CarbonImmutable    create($year = 0, $month = 1, $day = 1, $hour = 0, $minute = 0, $second = 0, $tz = null)                                       Create a new Carbon instance from a specific date and time.
 *                                                                                                                                                            If any of $year, $month or $day are set to null their now() values will
 *                                                                                                                                                            be used.
 *                                                                                                                                                            If $hour is null it will be set to its now() value and the default
 *                                                                                                                                                            values for $minute and $second will be their now() values.
 *                                                                                                                                                            If $hour is not null then the default values for $minute and $second
 *                                                                                                                                                            will be 0.
 * @method CarbonImmutable     createFromDate($year = null, $month = null, $day = null, $tz = null)                                                           Create a Carbon instance from just a date. The time portion is set to now.
 * @method ?CarbonImmutable    createFromFormat($format, $time, $tz = null)                                                                                   Create a Carbon instance from a specific format.
 * @method ?CarbonImmutable    createFromIsoFormat(string $format, string $time, $tz = null, ?string $locale = 'en', ?TranslatorInterface $translator = null) Create a Carbon instance from a specific ISO format (same replacements as ->isoFormat()).
 * @method ?CarbonImmutable    createFromLocaleFormat(string $format, string $locale, string $time, $tz = null)                                               Create a Carbon instance from a specific format and a string in a given language.
 * @method ?CarbonImmutable    createFromLocaleIsoFormat(string $format, string $locale, string $time, $tz = null)                                            Create a Carbon instance from a specific ISO format and a string in a given language.
 * @method CarbonImmutable     createFromTime($hour = 0, $minute = 0, $second = 0, $tz = null)                                                                Create a Carbon instance from just a time. The date portion is set to today.
 * @method CarbonImmutable     createFromTimeString($time, $tz = null)                                                                                        Create a Carbon instance from a time string. The date portion is set to today.
 * @method CarbonImmutable     createFromTimestamp(string|int|float $timestamp, DateTimeZone|string|int|null $tz = null)                                      Create a Carbon instance from a timestamp and set the timezone (use default one if not specified).
 *                                                                                                                                                            Timestamp input can be given as int, float or a string containing one or more numbers.
 * @method CarbonImmutable     createFromTimestampMs(string|int|float $timestamp, DateTimeZone|string|int|null $tz = null)                                    Create a Carbon instance from a timestamp in milliseconds.
 *                                                                                                                                                            Timestamp input can be given as int, float or a string containing one or more numbers.
 * @method CarbonImmutable     createFromTimestampMsUTC($timestamp)                                                                                           Create a Carbon instance from a timestamp in milliseconds.
 *                                                                                                                                                            Timestamp input can be given as int, float or a string containing one or more numbers.
 * @method CarbonImmutable     createFromTimestampUTC(string|int|float $timestamp)                                                                            Create a Carbon instance from an timestamp keeping the timezone to UTC.
 *                                                                                                                                                            Timestamp input can be given as int, float or a string containing one or more numbers.
 * @method CarbonImmutable     createMidnightDate($year = null, $month = null, $day = null, $tz = null)                                                       Create a Carbon instance from just a date. The time portion is set to midnight.
 * @method ?CarbonImmutable    createSafe($year = null, $month = null, $day = null, $hour = null, $minute = null, $second = null, $tz = null)                 Create a new safe Carbon instance from a specific date and time.
 *                                                                                                                                                            If any of $year, $month or $day are set to null their now() values will
 *                                                                                                                                                            be used.
 *                                                                                                                                                            If $hour is null it will be set to its now() value and the default
 *                                                                                                                                                            values for $minute and $second will be their now() values.
 *                                                                                                                                                            If $hour is not null then the default values for $minute and $second
 *                                                                                                                                                            will be 0.
 *                                                                                                                                                            If one of the set values is not valid, an InvalidDateException
 *                                                                                                                                                            will be thrown.
 * @method CarbonImmutable     createStrict(?int $year = 0, ?int $month = 1, ?int $day = 1, ?int $hour = 0, ?int $minute = 0, ?int $second = 0, $tz = null)   Create a new Carbon instance from a specific date and time using strict validation.
 * @method mixed               executeWithLocale(string $locale, callable $func)                                                                              Set the current locale to the given, execute the passed function, reset the locale to previous one,
 *                                                                                                                                                            then return the result of the closure (or null if the closure was void).
 * @method CarbonImmutable     fromSerialized($value)                                                                                                         Create an instance from a serialized string.
 * @method array               getAvailableLocales()                                                                                                          Returns the list of internally available locales and already loaded custom locales.
 *                                                                                                                                                            (It will ignore custom translator dynamic loading.)
 * @method Language[]          getAvailableLocalesInfo()                                                                                                      Returns list of Language object for each available locale. This object allow you to get the ISO name, native
 *                                                                                                                                                            name, region and variant of the locale.
 * @method array               getDays()                                                                                                                      Get the days of the week.
 * @method ?string             getFallbackLocale()                                                                                                            Get the fallback locale.
 * @method array               getFormatsToIsoReplacements()                                                                                                  List of replacements from date() format to isoFormat().
 * @method array               getIsoUnits()                                                                                                                  Returns list of locale units for ISO formatting.
 * @method array|false         getLastErrors()                                                                                                                {@inheritdoc}
 * @method string              getLocale()                                                                                                                    Get the current translator locale.
 * @method int                 getMidDayAt()                                                                                                                  get midday/noon hour
 * @method string              getTimeFormatByPrecision(string $unitPrecision)                                                                                Return a format from H:i to H:i:s.u according to given unit precision.
 * @method string|Closure|null getTranslationMessageWith($translator, string $key, ?string $locale = null, ?string $default = null)                           Returns raw translation message for a given key.
 * @method int                 getWeekEndsAt(?string $locale = null)                                                                                          Get the last day of week.
 * @method int                 getWeekStartsAt(?string $locale = null)                                                                                        Get the first day of week.
 * @method bool                hasRelativeKeywords(?string $time)                                                                                             Determine if a time string will produce a relative date.
 * @method CarbonImmutable     instance(DateTimeInterface $date)                                                                                              Create a Carbon instance from a DateTime one.
 * @method bool                isImmutable()                                                                                                                  Returns true if the current class/instance is immutable.
 * @method bool                isModifiableUnit($unit)                                                                                                        Returns true if a property can be changed via setter.
 * @method bool                isMutable()                                                                                                                    Returns true if the current class/instance is mutable.
 * @method bool                localeHasDiffOneDayWords(string $locale)                                                                                       Returns true if the given locale is internally supported and has words for 1-day diff (just now, yesterday, tomorrow).
 *                                                                                                                                                            Support is considered enabled if the 3 words are translated in the given locale.
 * @method bool                localeHasDiffSyntax(string $locale)                                                                                            Returns true if the given locale is internally supported and has diff syntax support (ago, from now, before, after).
 *                                                                                                                                                            Support is considered enabled if the 4 sentences are translated in the given locale.
 * @method bool                localeHasDiffTwoDayWords(string $locale)                                                                                       Returns true if the given locale is internally supported and has words for 2-days diff (before yesterday, after tomorrow).
 *                                                                                                                                                            Support is considered enabled if the 2 words are translated in the given locale.
 * @method bool                localeHasPeriodSyntax($locale)                                                                                                 Returns true if the given locale is internally supported and has period syntax support (X times, every X, from X, to X).
 *                                                                                                                                                            Support is considered enabled if the 4 sentences are translated in the given locale.
 * @method bool                localeHasShortUnits(string $locale)                                                                                            Returns true if the given locale is internally supported and has short-units support.
 *                                                                                                                                                            Support is considered enabled if either year, day or hour has a short variant translated.
 * @method ?CarbonImmutable    make($var)                                                                                                                     Make a Carbon instance from given variable if possible.
 *                                                                                                                                                            Always return a new instance. Parse only strings and only these likely to be dates (skip intervals
 *                                                                                                                                                            and recurrences). Throw an exception for invalid format, but otherwise return null.
 * @method void                mixin(object|string $mixin)                                                                                                    Mix another object into the class.
 * @method ?CarbonImmutable    parse(DateTimeInterface|WeekDay|Month|string|int|float|null $time, DateTimeZone|string|int|null $tz = null)                    Create a carbon instance from a string.
 *                                                                                                                                                            This is an alias for the constructor that allows better fluent syntax
 *                                                                                                                                                            as it allows you to do Carbon::parse('Monday next week')->fn() rather
 *                                                                                                                                                            than (new Carbon('Monday next week'))->fn().
 * @method CarbonImmutable     parseFromLocale(string $time, ?string $locale = null, DateTimeZone|string|int|null $tz = null)                                 Create a carbon instance from a localized string (in French, Japanese, Arabic, etc.).
 * @method string              pluralUnit(string $unit)                                                                                                       Returns standardized plural of a given singular/plural unit name (in English).
 * @method ?CarbonImmutable    rawCreateFromFormat(string $format, string $time, $tz = null)                                                                  Create a Carbon instance from a specific format.
 * @method ?CarbonImmutable    rawParse(DateTimeInterface|WeekDay|Month|string|int|float|null $time, DateTimeZone|string|int|null $tz = null)                 Create a carbon instance from a string.
 *                                                                                                                                                            This is an alias for the constructor that allows better fluent syntax
 *                                                                                                                                                            as it allows you to do Carbon::parse('Monday next week')->fn() rather
 *                                                                                                                                                            than (new Carbon('Monday next week'))->fn().
 * @method void                setFallbackLocale(string $locale)                                                                                              Set the fallback locale.
 * @method void                setLocale(string $locale)                                                                                                      Set the current translator locale and indicate if the source locale file exists.
 *                                                                                                                                                            Pass 'auto' as locale to use the closest language to the current LC_TIME locale.
 * @method void                setMidDayAt($hour)                                                                                                             @deprecated To avoid conflict between different third-party libraries, static setters should not be used.
 *                                                                                                                                                                        You should rather consider mid-day is always 12pm, then if you need to test if it's an other
 *                                                                                                                                                                        hour, test it explicitly:
 *                                                                                                                                                                            $date->format('G') == 13
 *                                                                                                                                                                        or to set explicitly to a given hour:
 *                                                                                                                                                                            $date->setTime(13, 0, 0, 0)
 *                                                                                                                                                            Set midday/noon hour
 * @method string              singularUnit(string $unit)                                                                                                     Returns standardized singular of a given singular/plural unit name (in English).
 * @method CarbonImmutable     today(DateTimeZone|string|int|null $tz = null)                                                                                 Create a Carbon instance for today.
 * @method CarbonImmutable     tomorrow(DateTimeZone|string|int|null $tz = null)                                                                              Create a Carbon instance for tomorrow.
 * @method string              translateTimeString(string $timeString, ?string $from = null, ?string $to = null, int $mode = CarbonInterface::TRANSLATE_ALL)  Translate a time string from a locale to an other.
 * @method string              translateWith(TranslatorInterface $translator, string $key, array $parameters = [], $number = null)                            Translate using translation string or callback available.
 * @method CarbonImmutable     yesterday(DateTimeZone|string|int|null $tz = null)                                                                             Create a Carbon instance for yesterday.
 *
 * </autodoc>
 */
class FactoryImmutable extends Factory implements ClockInterface
{
    protected string $className = CarbonImmutable::class;

    private static ?self $defaultInstance = null;

    private static ?WrapperClock $currentClock = null;

    /**
     * @internal Instance used for static calls, such as Carbon::getTranslator(), CarbonImmutable::setTestNow(), etc.
     */
    public static function getDefaultInstance(): self
    {
        return self::$defaultInstance ??= new self();
    }

    /**
     * @internal Instance used for static calls possibly called by non-static methods.
     */
    public static function getInstance(): Factory
    {
        return self::$currentClock?->getFactory() ?? self::$defaultInstance ??= new self();
    }

    /**
     * @internal Set instance before creating new dates.
     */
    public static function setCurrentClock(ClockInterface|Factory|DateTimeInterface|null $currentClock): void
    {
        if ($currentClock && !($currentClock instanceof WrapperClock)) {
            $currentClock = new WrapperClock($currentClock);
        }

        self::$currentClock = $currentClock;
    }

    /**
     * @internal Instance used to link new object to their factory creator.
     */
    public static function getCurrentClock(): ?WrapperClock
    {
        return self::$currentClock;
    }

    /**
     * Get a Carbon instance for the current date and time.
     */
    public function now(DateTimeZone|string|int|null $tz = null): CarbonImmutable
    {
        return $this->__call('now', [$tz]);
    }

    public function sleep(int|float $seconds): void
    {
        if ($this->hasTestNow()) {
            $this->setTestNow($this->getTestNow()->avoidMutation()->addSeconds($seconds));

            return;
        }

        (new NativeClock('UTC'))->sleep($seconds);
    }
}
