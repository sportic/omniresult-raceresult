<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests\Utility;

use Sportic\Omniresult\RaceResults\Tests\AbstractTest;
use Sportic\Omniresult\RaceResults\Utility\Races;

/**
 *
 */
class RacesTest extends AbstractTest
{
    /**
     * @param $in
     * @param $out
     * @return void
     * @dataProvider data_fromDataName
     */
    public function test_fromDataName($in, $out)
    {
        self::assertSame($out, Races::fromDataName($in));
    }

    public function data_fromDataName(): array
    {
        return [
            ['#1_test','test'],
            ['#3_ test',' test'],
            ['#1_2test','2test'],
            ['#10_test','test'],
            ['#1_Hervis Half Marathon','Hervis Half Marathon'],
            ['11 #1_ test','11 #1_ test'],
        ];
    }
}
