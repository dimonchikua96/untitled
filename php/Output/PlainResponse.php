<?php
/**
 * Created: 12.07.2017 10:40
 * User: it070389gds
 */

namespace App\Classes\Output;

class PlainResponse implements ResponseInterface
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

    public function response($data, $http_status)
    {

    }

    /**
     * @param string $data
     */
    public function goodResponse($data)
    {
        $this->ci_output->set_content_type('text/plain')
        ->set_status_header(200)
        ->set_output($data);
    }

    /**
     * @param int|string $errorCode
     * @param int|string $errorMessage
     * @param int $http_status_code
     */
    public function badResponse($errorCode, $errorMessage, $http_status_code = 500)
    {
        $this->ci_output->set_content_type('text/plain')
            ->set_status_header($http_status_code)
            ->set_output($errorMessage);
    }
}