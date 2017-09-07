<?php
/**
 * Created by PhpStorm.
 * User: it070389gds
 * Date: 14.11.2016
 * Time: 14:47
 */

namespace App\Classes\Helpers;
/**
 * Class GPSHelper
 * @package App\classes\Helpers
 */
class GPSHelper
{
    /**
     * Earth radius in meters
     */
    const EARTH_RADIUS = 6371228;

    /**
     * get
     * @param mixed $val
     * @return float
     */
    public static function getFormattedCoordinate($val = '')
    {
        $val = trim(str_replace(',', '.', $val));
        $val = explode('.', $val);
        return (float)$val[0] . '.' . substr($val[1] . str_repeat('0', 6), 0, 6);
    }

    /**
     * @param mixed $latitudeFrom
     * @param mixed $longitudeFrom
     * @param mixed $latitudeTo
     * @param mixed $longitudeTo
     * @return float
     */
    public static function getDistanceBetweenPoints($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo)
    {
        // convert from degrees to radians
        $latFrom = deg2rad(self::getFormattedCoordinate($latitudeFrom));
        $lonFrom = deg2rad(self::getFormattedCoordinate($longitudeFrom));
        $latTo = deg2rad(self::getFormattedCoordinate($latitudeTo));
        $lonTo = deg2rad(self::getFormattedCoordinate($longitudeTo));

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return  round($angle * self::EARTH_RADIUS,2);
    }
}
