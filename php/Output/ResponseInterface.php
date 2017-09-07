<?php
/**
 * Created: 12.07.2017 10:38
 * User: it070389gds
 */

namespace App\Classes\Output;


interface ResponseInterface
{
    /**
     * @param array|string $data
     */
    public function goodResponse($data);

    /**
     * @param int|string $errorCode
     * @param int|string $errorMessage
     * @param int $http_status_code
     */
    public function badResponse($errorCode, $errorMessage, $http_status_code);
}