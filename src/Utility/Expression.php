<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Utility;

/**
 *
 */
class Expression
{
    protected static $fieldMap = [
        'totalrankp' => 'posGen',
        'withstatus([agegrouprankp])' => 'posCategory',
        'withstatus([agegrouprank.p])' => 'posCategory',
        'agegrouprank' => 'posCategory',
        'withstatus([genderrankp])' => 'posGender',
        'withstatus([genderrank.p])' => 'posGender',
        'genderrank' => 'posGender',
        'bib' => 'bib',
        'nrconcurs' => 'bib',
        'flname' => 'fullNameFL',
        'lfname' => 'fullNameLF',
        'firstname' => 'lastName',
        'lastname' => 'lastName',
        'displayname' => 'fullName',
        'gendermf' => 'gender',
        'sexmf' => 'gender',
        'agegroupnameshort1' => 'category',
        'agegroupname1' => 'category',
        'agegroup.name' => 'category',
        'agegroup.nameshort' => 'category',
        'club' => 'club',
        'nation.flag' => 'country_flag',
        'time' => 'time',
        'finish.chip' => 'time',
        'finish.gun' => 'time',
        'chiptime' => 'time',
        'timetext300' => 'time',
        'timeorstatus' => 'time',
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
