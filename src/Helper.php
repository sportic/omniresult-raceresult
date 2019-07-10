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
}
