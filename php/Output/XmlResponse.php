<?php namespace App\Classes\Output;

/**
 * Created: 12.07.2017 10:38
 * User: it070389gds
 */

use App\Libraries\IvrInfo\ArrayToXml;

/**
 * Class XmlResponse
 * @package App\Classes\Output
 */
class XmlResponse implements ResponseInterface
{
    /**
     * Standard CI output object
     * @var
     */
    private $ci_output;

    /**
     * XmlResponse constructor.
     * @param $ci_output
     */
    public function __construct($ci_output)
    {
        $this->ci_output = $ci_output;
    }

    /**
     * @param array|string $data
     */
    public function goodResponse($data = array())
    {
        $this->ci_output->set_content_type('application/xml')
            ->set_status_header(200)
            ->set_output(
                is_array($data) ? ArrayToXml::toXml($data, 'data') : $data
            );
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param int $http_status_code
     */
    public function badResponse($errorCode, $errorMessage, $http_status_code = 500)
    {
        $this->ci_output->set_content_type('application/xml')
            ->set_status_header($http_status_code)
            ->set_output(ArrayToXml::toXml(array('code' => $errorCode, 'error_mess' => $errorMessage), 'result'));
    }
}