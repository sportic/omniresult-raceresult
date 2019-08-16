<?php

namespace Sportic\Omniresult\RaceResults\Tests;

use Sportic\Omniresult\RaceResults\Helper;

/**
 * Class HelperTest
 * @package Sportic\Omniresult\RaceResults\Tests
 */
class HelperTest extends AbstractTest
{
    /**
     * @param $name
     * @param $result
     * @dataProvider dataIsListCategory
     */
    public function testIsListCategory($name, $result)
    {
        self::assertSame($result, Helper::isListCategory($name));
    }

    /**
     * @return array
     */
    public function dataIsListCategory()
    {
        return [
            ['Male', false],
            ['Male U35', true],
            ['Male 35-44', true],
        ];
    }
}
