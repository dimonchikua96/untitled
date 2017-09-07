<?php
/**
 * Created: 13.07.2017 15:05
 * User: it070389gds
 */

namespace App\Classes\Exceptions;
use Exception;
use InvalidArgumentException;

class ApiException extends Exception
{
    private $http_status = null;
    protected $code;

    public function __construct($message = '', $code, $http_status = 500)
    {
        parent::__construct($message, 0);

        if (! isset($this->http_status)) {
            $this->setHttpStatus($http_status);
        }

        $this->code = $code;
    }

    public function setHttpStatus($http_status)
    {
        if (is_numeric($http_status)) {
            $this->http_status = $http_status;
        } else {
            throw new InvalidArgumentException("[" . var_export($http_status) . "] is not numeric value for HTTP status.");
        }
    }

    public function getHttpStatus()
    {
        return $this->http_status;
    }
}
