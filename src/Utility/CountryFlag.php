<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Utility;

/**
 *
 */
class CountryFlag
{
    /**
     * @param $string
     * @return string
     */
    public static function from($string): string
    {
        $trans = ['img:flags/' => '', '.gif' => '', ']' => '', '[' => ''];
        $string = strtr($string, $trans);
        return $string;
    }
}
