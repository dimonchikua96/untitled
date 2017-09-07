<?php namespace App\Classes\Output;

/**
 * Created: 12.07.2017 10:38
 * User: it070389gds
 */

/**
 * Class JsonResponse
 * @package App\Classes\Output
 */
class JsonResponse implements ResponseInterface
{
    /**
     * Standard CI output object
     * @var
     */
    private $ci_output;

    /**
     * JsonResponse constructor.
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
        $this->ci_output->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(
                is_array($data) ? json_encode($data, JSON_UNESCAPED_UNICODE) : $data
            );
    }

    /**
     * @param $errorCode
     * @param $errorMessage
     * @param int $http_status_code
     */
    public function badResponse($errorCode, $errorMessage, $http_status_code = 500)
    {
        $this->ci_output->set_content_type('application/json')
            ->set_status_header($http_status_code)
            ->set_output(json_encode(array('code' => $errorCode, 'error_mess' => $errorMessage), JSON_UNESCAPED_UNICODE));
    }
}