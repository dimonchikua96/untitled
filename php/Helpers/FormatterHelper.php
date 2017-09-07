<?php
/**
 * Created: 13.07.2017 21:36
 * User: it070389gds
 */

namespace App\Classes\Helpers;


class FormatterHelper
{
    public static function allQuotesToDoubleQuotes($string){
        return str_replace(array('«','»','”','”','“','”','”','‘','’'), '"', $string);
    }
}