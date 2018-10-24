<?php

namespace App\Doctrine\DoctrineExtensions\DBAL\Types;

use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use DateTimeZone;
use DateTime;

class UTCDateTimeType extends DateTimeType
{
    /**
     * @var DateTimeZone|null
     */
    private static $utc = null;

    /**
     * @param DateTime|null $value
     * @param AbstractPlatform $platform
     *
     * @return string|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        if (is_null(self::$utc)) {
            self::$utc = new DateTimeZone('UTC');
        }

        $value->setTimeZone(self::$utc);

        return $value->format($platform->getDateTimeFormatString());
    }

    /**
     * @param string|null $value
     * @param AbstractPlatform $platform
     *
     * @return DateTime|null
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $dateTime = DateTime::createFromFormat($platform->getDateTimeFormatString(), $value, new DateTimeZone(date_default_timezone_get()));

        if (!$dateTime) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }

        return $dateTime;
    }
}