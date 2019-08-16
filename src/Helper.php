<?php

namespace Sportic\Omniresult\RaceResults;

/**
 * Class Helper
 * @package Sportic\Omniresult\Trackmyrace
 */
class Helper extends \Sportic\Omniresult\Common\Helper
{

    /**
     * @param $paramsId
     * @return string
     */
    public static function encodeResultId($paramsId)
    {
        return base64_encode(serialize($paramsId));
    }

    /**
     * @param $id
     * @return array
     */
    public static function decodeResultId($id)
    {
        $idSerialized = base64_decode($id);
        return unserialize($idSerialized);
    }

    /**
     * @param $listName
     * @return bool
     */
    public static function isListCategory($listName)
    {
        $listName = strtolower($listName);
        $search = [
            ['male', '-'],
            ['male', 'u1'],
            ['male', 'u2'],
            ['male', 'u3'],
            ['female', '-'],
            ['female', 'u1'],
            ['female', 'u2'],
            ['female', 'u3']
        ];

        foreach ($search as $term) {
            if (is_array($term)) {
                $result = true;
                foreach ($term as $subTerm) {
                    $result = ($result && strpos($listName, $subTerm) !== false) ? true :false;
                }
                if ($result === true) {
                    return true;
                }
            } else {
                if (strpos($listName, $term) !== false) {
                    return true;
                }
            }
        }
        return false;
    }
}
