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

namespace Tests\Doctrine;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\Doctrine\CarbonImmutableType;
use Carbon\Doctrine\CarbonType;
use Carbon\Doctrine\CarbonTypeConverter;
use Carbon\Doctrine\DateTimeDefaultPrecision;
use Carbon\Doctrine\DateTimeImmutableType;
use Carbon\Doctrine\DateTimeType;
use DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Platforms\DB2Platform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\AbstractTestCase;
use Tests\Fixtures\CarbonTypeCase;

class CarbonTypesTest extends AbstractTestCase
{
    private static ?array $types = null;

    public static function setUpBeforeClass(): void
    {
        foreach (static::dataForTypes() as [$case]) {
            $case->initialize();
        }
    }

    public static function dataForTypes(): array
    {
        return self::$types ??= self::generateDataForTypes();
    }

    #[Group('doctrine')]
    #[DataProvider('dataForTypes')]
    public function testGetSQLDeclaration(CarbonTypeCase $case): void
    {
        $type = $case->getType();

        $adaptPrecisionToPlatform = method_exists(CarbonTypeConverter::class, 'getMaximumPrecision');
        $precision = DateTimeDefaultPrecision::get();
        $this->assertSame(6, $precision);
        $supportZeroPrecision = self::supportsZeroPrecision();

        $this->assertSame('DATETIME', $type->getSQLDeclaration($supportZeroPrecision ? [
            'precision' => 0,
        ] : [
            'precision' => null,
            'secondPrecision' => true,
        ], $this->getMySQLPlatform()));

        $this->assertSame('DATETIME(3)', $type->getSQLDeclaration([
            'precision' => 3,
        ], $this->getMySQLPlatform()));

        $this->assertSame('TIMESTAMP(0)', $type->getSQLDeclaration($supportZeroPrecision ? [
            'precision' => 0,
        ] : [
            'precision' => null,
            'secondPrecision' => true,
        ], new DB2Platform()));

        $this->assertSame('TIMESTAMP(6)', $type->getSQLDeclaration([
            'precision' => null,
        ], new DB2Platform()));

        $this->assertSame('TIMESTAMP(6)', $type->getSQLDeclaration($supportZeroPrecision ? [
            'precision' => null,
        ] : [
            'precision' => 0,
        ], new DB2Platform()));

        $this->assertSame('DATETIME(6)', $type->getSQLDeclaration($supportZeroPrecision ? [
            'precision' => null,
        ] : [
            'precision' => 0,
        ], $this->getMySQLPlatform()));

        $this->assertSame('DATETIME(6)', $type->getSQLDeclaration([
            'precision' => null,
        ], $this->getMySQLPlatform()));

        DateTimeDefaultPrecision::set(4);
        $this->assertSame('DATETIME(4)', $type->getSQLDeclaration([
            'precision' => null,
        ], $this->getMySQLPlatform()));

        DateTimeDefaultPrecision::set(9);
        $this->assertSame($adaptPrecisionToPlatform ? 'DATETIME(6)' : 'DATETIME(9)', $type->getSQLDeclaration([
            'precision' => null,
        ], $this->getMySQLPlatform()));

        DateTimeDefaultPrecision::set(0);
        $this->assertSame('DATETIME', $type->getSQLDeclaration([
            'precision' => null,
        ], $this->getMySQLPlatform()));

        DateTimeDefaultPrecision::set($precision);
    }

    #[Group('doctrine')]
    #[DataProvider('dataForTypes')]
    public function testConvertToPHPValue(CarbonTypeCase $case): void
    {
        $type = $case->getType();

        $this->assertNull($type->convertToPHPValue(null, $this->getMySQLPlatform()));

        $date = $type->convertToPHPValue(Carbon::parse('2020-06-23 18:47'), $this->getMySQLPlatform());
        $this->assertInstanceOf($case->class, $date);
        $this->assertSame('2020-06-23 18:47:00.000000', $date->format('Y-m-d H:i:s.u'));

        $date = $type->convertToPHPValue(new DateTimeImmutable('2020-06-23 18:47'), $this->getMySQLPlatform());
        $this->assertInstanceOf($case->class, $date);
        $this->assertSame('2020-06-23 18:47:00.000000', $date->format('Y-m-d H:i:s.u'));

        $date = $type->convertToPHPValue('2020-06-23 18:47', $this->getMySQLPlatform());
        $this->assertInstanceOf($case->class, $date);
        $this->assertSame('2020-06-23 18:47:00.000000', $date->format('Y-m-d H:i:s.u'));
    }

    #[Group('doctrine')]
    #[DataProvider('dataForTypes')]
    public function testConvertToPHPValueFailure(CarbonTypeCase $case): void
    {
        $conversion = version_compare(self::getDbalVersion(), '4.0.0', '>=')
            ? "to \"$case->typeClass\" as an error was triggered by the unserialization: "
            : "\"2020-0776-23 18:47\" to Doctrine Type $case->name. Expected format: ";
        $this->expectExceptionObject(new ConversionException(
            'Could not convert database value '.$conversion.
            "Y-m-d H:i:s.u or any format supported by $case->class::parse()",
        ));

        $case->getType()->convertToPHPValue('2020-0776-23 18:47', $this->getMySQLPlatform());
    }

    #[Group('doctrine')]
    #[DataProvider('dataForTypes')]
    public function testConvertToDatabaseValue(CarbonTypeCase $case): void
    {
        $type = $case->getType();

        $this->assertNull($type->convertToDatabaseValue(null, $this->getMySQLPlatform()));
        $this->assertSame(
            '2020-06-23 18:47:00.000000',
            $type->convertToDatabaseValue(new DateTimeImmutable('2020-06-23 18:47'), $this->getMySQLPlatform()),
        );
    }

    #[Group('doctrine')]
    #[DataProvider('dataForTypes')]
    public function testConvertToDatabaseValueFailure(CarbonTypeCase $case): void
    {
        $quote = class_exists('Doctrine\\DBAL\\Version') ? "'" : '';
        $conversion = version_compare(self::getDbalVersion(), '4.0.0', '>=')
            ? "array to type $case->typeClass. "
            : "{$quote}array{$quote} to type {$quote}$case->name{$quote}. ";
        $this->expectExceptionObject(new ConversionException(
            'Could not convert PHP value of type '.$conversion.
            'Expected one of the following types: null, DateTime, Carbon',
        ));

        $case->getType()->convertToDatabaseValue([2020, 06, 23], $this->getMySQLPlatform());
    }

    #[Group('doctrine')]
    #[DataProvider('dataForTypes')]
    public function testRequiresSQLCommentHint(CarbonTypeCase $case): void
    {
        if (version_compare(self::getDbalVersion(), '4.0.0', '>=')) {
            $this->markTestSkipped('requiresSQLCommentHint dropped since DBAL 4');
        }

        $this->assertSame(
            $case->hintRequired,
            $case->getType()->requiresSQLCommentHint($this->getMySQLPlatform()),
        );
    }

    private static function getDbalVersion(): string
    {
        static $dbalVersion = null;

        if ($dbalVersion === null) {
            $installed = require __DIR__.'/../../vendor/composer/installed.php';
            $dbalVersion = $installed['versions']['doctrine/dbal']['version'] ?? '2.0.0';
        }

        return $dbalVersion;
    }

    private static function supportsZeroPrecision(): bool
    {
        return version_compare(self::getDbalVersion(), '3.7.0', '>=');
    }

    private function getMySQLPlatform(): AbstractMySQLPlatform
    {
        return class_exists(MySQLPlatform::class) ? new MySQLPlatform() : new MySQL57Platform();
    }

    private static function generateDataForTypes(): array
    {
        $supportZeroPrecision = self::supportsZeroPrecision();

        $types = [
            [new CarbonTypeCase($supportZeroPrecision ? 'date_time' : 'datetime', Carbon::class, DateTimeType::class, false)],
            [new CarbonTypeCase($supportZeroPrecision ? 'date_time_immutable' : 'datetime_immutable', CarbonImmutable::class, DateTimeImmutableType::class, true)],
            [new CarbonTypeCase('carbon', Carbon::class, CarbonType::class, !$supportZeroPrecision)],
            [new CarbonTypeCase('carbon_immutable', CarbonImmutable::class, CarbonImmutableType::class, true)],
        ];

        return array_combine(array_column($types, 0), $types);
    }
}
