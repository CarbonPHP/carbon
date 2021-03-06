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

namespace Tests\Localization;

/**
 * @group localization
 */
class CkbTest extends LocalizationTestCase
{
    public const LOCALE = 'ckb'; // ckb

    public const CASES = [
        // Carbon::parse('2018-01-04 00:00:00')->addDays(1)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'سبەینێ لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->addDays(2)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'شەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->addDays(3)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'یەکشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->addDays(4)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'دووشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->addDays(5)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'سێشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->addDays(6)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'چوارشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-05 00:00:00')->addDays(6)->calendar(Carbon::parse('2018-01-05 00:00:00'))
        'پێنجشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-06 00:00:00')->addDays(6)->calendar(Carbon::parse('2018-01-06 00:00:00'))
        'هەینی لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-07 00:00:00')->addDays(2)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'سێشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-07 00:00:00')->addDays(3)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'چوارشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-07 00:00:00')->addDays(4)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'پێنجشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-07 00:00:00')->addDays(5)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'هەینی لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-07 00:00:00')->addDays(6)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'شەممە لە کاتژمێر 00:00',
        // Carbon::now()->subDays(2)->calendar()
        'یەکشەممە لە کاتژمێر 20:49',
        // Carbon::parse('2018-01-04 00:00:00')->subHours(2)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'دوێنێ لە کاتژمێر 22:00',
        // Carbon::parse('2018-01-04 12:00:00')->subHours(2)->calendar(Carbon::parse('2018-01-04 12:00:00'))
        'ئەمڕۆ لە کاتژمێر 10:00',
        // Carbon::parse('2018-01-04 00:00:00')->addHours(2)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'ئەمڕۆ لە کاتژمێر 02:00',
        // Carbon::parse('2018-01-04 23:00:00')->addHours(2)->calendar(Carbon::parse('2018-01-04 23:00:00'))
        'سبەینێ لە کاتژمێر 01:00',
        // Carbon::parse('2018-01-07 00:00:00')->addDays(2)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'سێشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-08 00:00:00')->subDay()->calendar(Carbon::parse('2018-01-08 00:00:00'))
        'دوێنێ لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->subDays(1)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'دوێنێ لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->subDays(2)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'سێشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->subDays(3)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'دووشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->subDays(4)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'یەکشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->subDays(5)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'شەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-04 00:00:00')->subDays(6)->calendar(Carbon::parse('2018-01-04 00:00:00'))
        'هەینی لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-03 00:00:00')->subDays(6)->calendar(Carbon::parse('2018-01-03 00:00:00'))
        'پێنجشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-02 00:00:00')->subDays(6)->calendar(Carbon::parse('2018-01-02 00:00:00'))
        'چوارشەممە لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-07 00:00:00')->subDays(2)->calendar(Carbon::parse('2018-01-07 00:00:00'))
        'هەینی لە کاتژمێر 00:00',
        // Carbon::parse('2018-01-01 00:00:00')->isoFormat('Qo Mo Do Wo wo')
        '1 1 1 1 1',
        // Carbon::parse('2018-01-02 00:00:00')->isoFormat('Do wo')
        '2 1',
        // Carbon::parse('2018-01-03 00:00:00')->isoFormat('Do wo')
        '3 1',
        // Carbon::parse('2018-01-04 00:00:00')->isoFormat('Do wo')
        '4 1',
        // Carbon::parse('2018-01-05 00:00:00')->isoFormat('Do wo')
        '5 1',
        // Carbon::parse('2018-01-06 00:00:00')->isoFormat('Do wo')
        '6 2',
        // Carbon::parse('2018-01-07 00:00:00')->isoFormat('Do wo')
        '7 2',
        // Carbon::parse('2018-01-11 00:00:00')->isoFormat('Do wo')
        '11 2',
        // Carbon::parse('2018-02-09 00:00:00')->isoFormat('DDDo')
        '40',
        // Carbon::parse('2018-02-10 00:00:00')->isoFormat('DDDo')
        '41',
        // Carbon::parse('2018-04-10 00:00:00')->isoFormat('DDDo')
        '100',
        // Carbon::parse('2018-02-10 00:00:00', 'Europe/Paris')->isoFormat('h:mm a z')
        '12:00 پ.ن CET',
        // Carbon::parse('2018-02-10 00:00:00')->isoFormat('h:mm A, h:mm a')
        '12:00 پ.ن, 12:00 پ.ن',
        // Carbon::parse('2018-02-10 01:30:00')->isoFormat('h:mm A, h:mm a')
        '1:30 پ.ن, 1:30 پ.ن',
        // Carbon::parse('2018-02-10 02:00:00')->isoFormat('h:mm A, h:mm a')
        '2:00 پ.ن, 2:00 پ.ن',
        // Carbon::parse('2018-02-10 06:00:00')->isoFormat('h:mm A, h:mm a')
        '6:00 پ.ن, 6:00 پ.ن',
        // Carbon::parse('2018-02-10 10:00:00')->isoFormat('h:mm A, h:mm a')
        '10:00 پ.ن, 10:00 پ.ن',
        // Carbon::parse('2018-02-10 12:00:00')->isoFormat('h:mm A, h:mm a')
        '12:00 د.ن, 12:00 د.ن',
        // Carbon::parse('2018-02-10 17:00:00')->isoFormat('h:mm A, h:mm a')
        '5:00 د.ن, 5:00 د.ن',
        // Carbon::parse('2018-02-10 21:30:00')->isoFormat('h:mm A, h:mm a')
        '9:30 د.ن, 9:30 د.ن',
        // Carbon::parse('2018-02-10 23:00:00')->isoFormat('h:mm A, h:mm a')
        '11:00 د.ن, 11:00 د.ن',
        // Carbon::parse('2018-01-01 00:00:00')->ordinal('hour')
        '0',
        // Carbon::now()->subSeconds(1)->diffForHumans()
        'پێشتر چرکە',
        // Carbon::now()->subSeconds(1)->diffForHumans(null, false, true)
        'پێشتر چرکە',
        // Carbon::now()->subSeconds(2)->diffForHumans()
        'پێشتر چرکە',
        // Carbon::now()->subSeconds(2)->diffForHumans(null, false, true)
        'پێشتر چرکە',
        // Carbon::now()->subMinutes(1)->diffForHumans()
        'پێشتر خولەک',
        // Carbon::now()->subMinutes(1)->diffForHumans(null, false, true)
        'پێشتر خولەک',
        // Carbon::now()->subMinutes(2)->diffForHumans()
        'پێشتر خولەک',
        // Carbon::now()->subMinutes(2)->diffForHumans(null, false, true)
        'پێشتر خولەک',
        // Carbon::now()->subHours(1)->diffForHumans()
        'پێشتر کاتژمێر',
        // Carbon::now()->subHours(1)->diffForHumans(null, false, true)
        'پێشتر کاتژمێر',
        // Carbon::now()->subHours(2)->diffForHumans()
        'پێشتر کاتژمێر',
        // Carbon::now()->subHours(2)->diffForHumans(null, false, true)
        'پێشتر کاتژمێر',
        // Carbon::now()->subDays(1)->diffForHumans()
        'پێشتر ڕۆژ',
        // Carbon::now()->subDays(1)->diffForHumans(null, false, true)
        'پێشتر ڕۆژ',
        // Carbon::now()->subDays(2)->diffForHumans()
        'پێشتر ڕۆژ',
        // Carbon::now()->subDays(2)->diffForHumans(null, false, true)
        'پێشتر ڕۆژ',
        // Carbon::now()->subWeeks(1)->diffForHumans()
        'پێشتر هەفتە',
        // Carbon::now()->subWeeks(1)->diffForHumans(null, false, true)
        'پێشتر هەفتە',
        // Carbon::now()->subWeeks(2)->diffForHumans()
        'پێشتر هەفتە',
        // Carbon::now()->subWeeks(2)->diffForHumans(null, false, true)
        'پێشتر هەفتە',
        // Carbon::now()->subMonths(1)->diffForHumans()
        'پێشتر مانگ',
        // Carbon::now()->subMonths(1)->diffForHumans(null, false, true)
        'پێشتر مانگ',
        // Carbon::now()->subMonths(2)->diffForHumans()
        'پێشتر مانگين',
        // Carbon::now()->subMonths(2)->diffForHumans(null, false, true)
        'پێشتر مانگين',
        // Carbon::now()->subYears(1)->diffForHumans()
        'پێشتر ساڵ',
        // Carbon::now()->subYears(1)->diffForHumans(null, false, true)
        'پێشتر ساڵ',
        // Carbon::now()->subYears(2)->diffForHumans()
        'پێشتر ساڵ',
        // Carbon::now()->subYears(2)->diffForHumans(null, false, true)
        'پێشتر ساڵ',
        // Carbon::now()->addSecond()->diffForHumans()
        'چرکە لە ئێستاوە',
        // Carbon::now()->addSecond()->diffForHumans(null, false, true)
        'چرکە لە ئێستاوە',
        // Carbon::now()->addSecond()->diffForHumans(Carbon::now())
        'دوای چرکە',
        // Carbon::now()->addSecond()->diffForHumans(Carbon::now(), false, true)
        'دوای چرکە',
        // Carbon::now()->diffForHumans(Carbon::now()->addSecond())
        'پێش چرکە',
        // Carbon::now()->diffForHumans(Carbon::now()->addSecond(), false, true)
        'پێش چرکە',
        // Carbon::now()->addSecond()->diffForHumans(Carbon::now(), true)
        'چرکە',
        // Carbon::now()->addSecond()->diffForHumans(Carbon::now(), true, true)
        'چرکە',
        // Carbon::now()->diffForHumans(Carbon::now()->addSecond()->addSecond(), true)
        'چرکە',
        // Carbon::now()->diffForHumans(Carbon::now()->addSecond()->addSecond(), true, true)
        'چرکە',
        // Carbon::now()->addSecond()->diffForHumans(null, false, true, 1)
        'چرکە لە ئێستاوە',
        // Carbon::now()->addMinute()->addSecond()->diffForHumans(null, true, false, 2)
        'خولەک چرکە',
        // Carbon::now()->addYears(2)->addMonths(3)->addDay()->addSecond()->diffForHumans(null, true, true, 4)
        'ساڵ 3 مانگ ڕۆژ چرکە',
        // Carbon::now()->addYears(3)->diffForHumans(null, null, false, 4)
        '3 ساڵ لە ئێستاوە',
        // Carbon::now()->subMonths(5)->diffForHumans(null, null, true, 4)
        'پێشتر 5 مانگ',
        // Carbon::now()->subYears(2)->subMonths(3)->subDay()->subSecond()->diffForHumans(null, null, true, 4)
        'پێشتر ساڵ 3 مانگ ڕۆژ چرکە',
        // Carbon::now()->addWeek()->addHours(10)->diffForHumans(null, true, false, 2)
        'هەفتە 10 کاتژمێر',
        // Carbon::now()->addWeek()->addDays(6)->diffForHumans(null, true, false, 2)
        'هەفتە 6 ڕۆژ',
        // Carbon::now()->addWeek()->addDays(6)->diffForHumans(null, true, false, 2)
        'هەفتە 6 ڕۆژ',
        // Carbon::now()->addWeek()->addDays(6)->diffForHumans(["join" => true, "parts" => 2])
        'هەفتە و 6 ڕۆژ لە ئێستاوە',
        // Carbon::now()->addWeeks(2)->addHour()->diffForHumans(null, true, false, 2)
        'هەفتە کاتژمێر',
        // Carbon::now()->addHour()->diffForHumans(["aUnit" => true])
        'کاتژمێر لە ئێستاوە',
        // CarbonInterval::days(2)->forHumans()
        'ڕۆژ',
        // CarbonInterval::create('P1DT3H')->forHumans(true)
        'ڕۆژ 3 کاتژمێر',
    ];
}
