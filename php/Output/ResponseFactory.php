<?php namespace App\Classes\Output;

/**
 * Created: 12.07.2017 10:14
 * User: it070389gds
 */

/**
 * Class ResponseFactory
 * @package App\Classes\Output
 */
class ResponseFactory
{
    /**
     * Supported formats for response
     */
    const SUPPORTED_FORMATS = array('xml', 'json');

    /**
     * JSON_FORMAT const
     */
    const JSON_FORMAT = 'json';

    /**
     * XML_FORMAT const
     */
    const XML_FORMAT = 'xml';

    /**
     * @param $format
     * @param $ci_output
     * @return JsonResponse|XmlResponse|PlainResponse
     */
    public static function make($format, $ci_output)
    {
        if (in_array($format, self::SUPPORTED_FORMATS)) {
            $response_class_name = "App\Classes\Output\\". ucfirst($format) . "Response";
            return new $response_class_name($ci_output);
        } else {
            return new PlainResponse($ci_output);
        }
    }
}