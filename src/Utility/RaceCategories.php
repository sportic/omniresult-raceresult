<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Utility;

/**
 *
 */
class RaceCategories
{
    /**
     * @param $listName
     * @return bool
     */
    public static function isListCategory($listName): bool
    {
        $listName = (string) $listName;
        $listName = strtolower($listName);
        $search = [
            ['male', '-'],
            ['masculin'],
            ['male', 'u1'],
            ['male', 'u2'],
            ['male', 'u3'],
            ['feminin'],
            ['female', '-'],
            ['female', 'u1'],
            ['female', 'u2'],
            ['female', 'u3']
        ];

        foreach ($search as $term) {
            if (is_array($term)) {
                $result = true;
                foreach ($term as $subTerm) {
                    $result = ($result && strpos($listName, $subTerm) !== false) ? true : false;
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

    /**
     * @param $listName
     * @return string
     */
    public static function fromListName($listName): string
    {
        $numbers = range(1, 20);
        foreach ($numbers as $digit) {
            $listName = str_replace('#' . $digit . '_', '', $listName);
        }
        return $listName;
    }
}
