<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use Sportic\Omniresult\RaceResults\Utility\RaceCategories;

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
    #[DataProvider('dataIsListCategory')]
    public function testIsListCategory($name, $result)
    {
        self::assertSame($result, RaceCategories::isListCategory($name));
    }

    /**
     * @return array
     */
    public static function dataIsListCategory(): array
    {
        return [
            ['Male', false],
            ['Male U35', true],
            ['Male 35-44', true],
        ];
    }
}
