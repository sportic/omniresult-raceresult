<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Utility;

use Sportic\Omniresult\Common\Models\Race;

/**
 *
 */
class Races
{
    public static function dataName(?Race $race): ?string
    {
        if ($race === null) {
            return null;
        }
        return '#' . $race->getId() . '_' . $race->getName();
    }

    /**
     * @param string $name
     * @return string
     */
    public static function fromDataName(string $name): string
    {
        return preg_replace('$^#(\d{1,2})_$','', $name);
    }
}
