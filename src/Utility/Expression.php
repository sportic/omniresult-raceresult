<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Utility;

/**
 *
 */
class Expression
{
    protected static $fieldMap = [
        'withstatus([agegrouprankp])' => 'posCategory',
        'withstatus([agegrouprank.p])' => 'posCategory',
        'agegrouprank' => 'posCategory',
        'withstatus([genderrankp])' => 'posGender',
        'withstatus([genderrank.p])' => 'posGender',
        'genderrank' => 'posGender',
        'bib' => 'bib',
        'flname' => 'fullNameFL',
        'displayname' => 'fullName',
        'gendermf' => 'gender',
        'agegroupnameshort1' => 'category',
        'agegroupname1' => 'category',
        'agegroup.name' => 'category',
        'club' => 'club',
        'nation.flag' => 'country_flag',
        'time' => 'time',
        'finish.chip' => 'time',
        'chiptime' => 'time',
        'timetext300' => 'time',
        'guntime' => 'time_gross',
        'timetext' => 'time_gross',
        'distancefinal&"m"' => 'result'
    ];

    /**
     * @param $name
     * @return string
     */
    public static function format($name): string
    {
        $trans = [' ' => ''];
        $name = strtolower($name);
        $name = strtr($name, $trans);
        return $name;
    }

    /**
     * @param $expression
     * @return string|null
     */
    public static function toResultField($expression)
    {
        $expression = self::format($expression);
        return static::$fieldMap[$expression] ?? null;
    }
}
