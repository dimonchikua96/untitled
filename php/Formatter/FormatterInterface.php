<?php
/**
 * Created: 13.07.2017 9:56
 * User: it070389gds
 */

namespace App\Classes\Formatter;


interface FormatterInterface
{
    /**
     * @param array $data
     * @param string $format
     * @return mixed
     */
    public static function format(array $data, $format);
}