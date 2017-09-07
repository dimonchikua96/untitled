<?php namespace App\Classes\Formatter\Device;

/**
 * Created: 13.07.2017 9:53
 * User: it070389gds
 */

use App\Classes\Formatter\FormatterInterface;
use App\Classes\Helpers\FormatterHelper;
use App\Classes\Helpers\GPSHelper;
use App\Classes\Libraries\XmlWriter;

class GpsDataShortFormFormatterHelper extends FormatterHelper implements FormatterInterface
{
    /**
     * @param array $data
     * @param string $format
     * @return mixed
     */
    public static function format(array $data, $format)
    {
        if ($format == 'json') {
            $response = [];
            foreach ($data as $row) {
                $response[] = array(
                    "compassname" => $row['CompassName'],
                    "coord" => [
                        "lon" => GPSHelper::getFormattedCoordinate($row['lat']),
                        "lat" => GPSHelper::getFormattedCoordinate($row['lon'])
                    ],
                    "addr" => $row['kladr_code'],
                    "tw" => [
                        "mon" => $row['mon'],
                        "tue" => $row['tue'],
                        "wed" => $row['wed'],
                        "thu" => $row['thu'],
                        "fri" => $row['fri'],
                        "sat" => $row['sat'],
                        "sun" => $row['sun'],
                        "hol" => $row['hol']
                    ],
                    "place" => [
                        "rus" => FormatterHelper::allQuotesToDoubleQuotes($row['place']),
                        "ukr" => FormatterHelper::allQuotesToDoubleQuotes($row['place_ukr'])
                    ],
                    "type" => $row['type']
                );
            }

            return $response;
        } else {
            $response = new XmlWriter();
            $response->XmlWriter();
            $response->push('data');

            foreach ($data as $row) {

                $response->push('device');
                $response->element('compassname', $row['CompassName']);

                $response->push('coord');
                $response->element('lat', GPSHelper::getFormattedCoordinate($row['lat']));
                $response->element('lon', GPSHelper::getFormattedCoordinate($row['lon']));
                $response->pop();

                $response->element('addr', $row['kladr_code']);

                $response->push('tw');
                $response->element('mon', $row['mon']);
                $response->element('tue', $row['tue']);
                $response->element('wed', $row['wed']);
                $response->element('thu', $row['thu']);
                $response->element('fri', $row['fri']);
                $response->element('sat', $row['sat']);
                $response->element('sun', $row['sun']);
                $response->element('hol', $row['hol']);
                $response->pop();

                $response->push('place');
                $response->element('rus', FormatterHelper::allQuotesToDoubleQuotes($row['place']));
                $response->element('ukr', FormatterHelper::allQuotesToDoubleQuotes($row['place_ukr']));
                $response->pop();

                $response->element('type', $row['type']);
                $response->pop();
            }
            $response->pop();

            return $response->getXml();
        }
    }
}