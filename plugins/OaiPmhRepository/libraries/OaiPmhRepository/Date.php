<?php
/**
 * @package OaiPmhRepository
 * @subpackage Libraries
 * @author John Flatness
 * @copyright Copyright 2012 John Flatness
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */


/**
 * Class containing static functions for date tasks.
 *
 * @package OaiPmhRepository
 * @subpackage Libraries
 */
class OaiPmhRepository_Date {

    const OAI_DATE_PCRE     = "/^\\d{4}\\-\\d{2}\\-\\d{2}$/";
    const OAI_DATETIME_PCRE = "/^\\d{4}\\-\\d{2}\\-\\d{2}T\\d{2}\\:\\d{2}\\:\\d{2}Z$/";
    
    const OAI_GRANULARITY_STRING   = 'YYYY-MM-DDThh:mm:ssZ';
    const OAI_GRANULARITY_DATE     = 1;
    const OAI_GRANULARITY_DATETIME = 2;

    /**
     * PHP date() format string to produce the required date format.
     * Must be used with gmdate() to conform to spec.
     */
    const OAI_DATE_FORMAT = 'Y-m-d\TH:i:s\Z';
    const DB_DATE_FORMAT  = 'Y-m-d H:i:s';
    
    /**
     * Converts the given Unix timestamp to OAI-PMH's specified ISO 8601 format.
     *
     * @param int $timestamp Unix timestamp
     * @return string Time in ISO 8601 format
     */
    public static function unixToUtc($timestamp)
    {
        return gmdate(self::OAI_DATE_FORMAT, $timestamp);
    }
    
    /**
     * Converts the given Unix timestamp to the Omeka DB's datetime format.
     *
     * @param int $timestamp Unix timestamp
     * @return string Time in Omeka DB format
     */
    public static function unixToDb($timestamp)
    {
       return date(self::DB_DATE_FORMAT, $timestamp);
    }

    /**
     * Converts the given time string to OAI-PMH's specified ISO 8601 format.
     * Used to convert the timestamps output from the Omeka database.
     *
     * @param string $databaseTime Database time string
     * @return string Time in ISO 8601 format
     * @uses unixToUtc()
     */
    public static function dbToUtc($databaseTime)
    {
        return self::unixToUtc(strtotime($databaseTime));
    }
    
    /**
     * Converts the given time string to the Omeka database's format.
     *
     * @param string $databaseTime Database time string
     * @param bool $until Whether this is an "until" date (one unit of granularity is added).
     * @return string Time in Omeka DB format
     * @uses unixToDb()
     */
    public static function utcToDb($utcDateTime, $until = false)
    {
        // Make sure day-granularity dates are interpreted as UTC
        $day = substr($utcDateTime, -1) != 'Z';
        if ($day) {
            $utcDateTime .= 'Z';
        }

        // Interpret an "until" date as being the next-latest granularity unit
        // (until dates are compared using an exclusive operator but must act inclusive)
        if ($until) {
            if ($day) {
                $utcDateTime .= ' +1 day';
            } else {
                $utcDateTime .= ' +1 second';
            }
        }
        return self::unixToDb(strtotime($utcDateTime));
    }

    /**
     * Returns the granularity of the given utcDateTime string.  Returns zero
     * if the given string is not in utcDateTime format.
     *
     * @param string $dateTime Time string
     * @return int OAI_GRANULARITY_DATE, OAI_GRANULARITY_DATETIME, or zero
     */
    public static function getGranularity($dateTime)
    {
        if (preg_match(self::OAI_DATE_PCRE, $dateTime)) {
            return self::OAI_GRANULARITY_DATE;
        } else if (preg_match(self::OAI_DATETIME_PCRE, $dateTime)) {
            return self::OAI_GRANULARITY_DATETIME;
        } else {
            return false;
        }
    }
}
