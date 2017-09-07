<?php namespace App\Classes\Formatter\Device;

/**
 * Created: 13.07.2017 9:53
 * User: it070389gds
 */

use App\Classes\Formatter\FormatterInterface;
use App\Classes\Helpers\GPSHelper;
use App\Classes\Libraries\XmlWriter;
use App\Classes\Helpers\FormatterHelper;

class GpsDataFullFormFormatter implements FormatterInterface
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
                $row_arr = array(
                    "compassname" => $row['CompassName'],
                    "state" => $row['state'],
                    "addr" => $row['kladr_code'],
                    "type" => $row['type'],
                    "bank_module" => [
                        "id" => $row['bank_module_id'],
                        "bank_module_name" => $row['bank_module_name']
                    ],
                    "eca_addr" => $row['address'],

                    "place" => [
                        "rus" => FormatterHelper::allQuotesToDoubleQuotes($row['place']),
                        "ukr" => FormatterHelper::allQuotesToDoubleQuotes($row['place_ukr'])
                    ],
                    "coord" => [
                        "lon" => GPSHelper::getFormattedCoordinate($row['lat']),
                        "lat" => GPSHelper::getFormattedCoordinate($row['lon'])
                    ],
                    "tw" => [
                        "mon" => $row['mon'],
                        "tue" => $row['tue'],
                        "wed" => $row['wed'],
                        "thu" => $row['thu'],
                        "fri" => $row['fri'],
                        "sat" => $row['sat'],
                        "sun" => $row['sun'],
                        "hol" => $row['hol']
                    ]);

                if ($row['device_type'] == "atm") {

                    if ($row['Curr_1'] == "USD" || $row['Curr_2'] == "USD" || $row['Curr_3'] == "USD" || $row['Curr_4'] == "USD") {
                        $curr_exchange = 1;
                    } else {
                        $curr_exchange = 0;
                    }

                    $row_arr['cassets'] = array(
                        'F1' => $row['F1_CUR'],
                        'F2' => $row['F2_CUR'],
                        'F3' => $row['F3_CUR'],
                        'F4' => $row['F4_CUR'],
                    );

                    $row_arr["curr_exchange"] = $curr_exchange;
                } else {
                    $row_arr['os'] = array(
                        'osType' => $row['type_os'],
                        'description' => $row['os_descr'],
                        'poType' => $row['po_type'],
                        'terminalType' => $row['terminalType'],
                    );
                }

                $response[] = $row_arr;
            }

            return $response;
        } else {
            $response = new XmlWriter();
            $response->XmlWriter();
            $response->push('data');

            foreach ($data as $row) {

                $response->push('device');
                $response->element('compassname', $row['CompassName']);
                $response->element('state', $row['state']);
                $response->element('addr', $row['kladr_code']);
                $response->element('type', $row['type']);

                $response->push('bank_module');
                $response->element('id', $row['bank_module_id']);
                $response->element('bank_module_name', $row['bank_module_name']);
                $response->pop();

                $response->xml_element('eca_addr', $row['address']);

                $response->push('place');
                $response->element('rus', FormatterHelper::allQuotesToDoubleQuotes($row['place']));
                $response->element('ukr', FormatterHelper::allQuotesToDoubleQuotes($row['place_ukr']));
                $response->pop();

                $response->push('coord');
                $response->element('lat', GPSHelper::getFormattedCoordinate($row['lat']));
                $response->element('lon', GPSHelper::getFormattedCoordinate($row['lon']));
                $response->pop();

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

                if ($row['device_type'] == "atm") {

                    if ($row['Curr_1'] == "USD" || $row['Curr_2'] == "USD" || $row['Curr_3'] == "USD" || $row['Curr_4'] == "USD") {
                        $curr_exchange = 1;
                    } else {
                        $curr_exchange = 0;
                    }

                    $response->push('cassets');
                    $response->element('F1', $row['F1_CUR']);
                    $response->element('F2', $row['F2_CUR']);
                    $response->element('F3', $row['F3_CUR']);
                    $response->element('F4', $row['F4_CUR']);
                    $response->pop();

                    $response->element('curr_exchange', $curr_exchange);

                } else {
                    $response->push('os');
                    $response->element('osType', $row['type_os']);
                    $response->element('description', $row['os_descr']);
                    $response->element('poType', $row['po_type']);
                    $response->element('terminalType', $row['terminalType']);
                    $response->pop();
                }

                $response->pop();
            }
            $response->pop();
            return $response->getXml();
        }
    }
}