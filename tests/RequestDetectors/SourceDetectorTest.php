<?php

namespace Sportic\Omniresult\RaceResults\Tests\RequestDetectors;

use Sportic\Omniresult\Common\RequestDetector\DetectorResult;
use Sportic\Omniresult\Common\RequestDetector\Detectors\AbstractSourceDetector;
use Sportic\Omniresult\RaceResults\RequestDetectors\SourceDetector;
use Sportic\Omniresult\RaceResults\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SourceDetectorTest
 * @package Sportic\Omniresult\RaceResults\Tests\RequestDetectors
 */
class SourceDetectorTest extends AbstractTest
{
    public static function dataDetect()
    {
        return [
            ['https://my-run.ro/wizz-air-cluj-napoca-marathon-2019-rezultate/','event', ['eventid' => 122816]],
            ['https://my-run.ro/honey-run-2024-rezultate/','event', ['eventid' => 293308]],
        ];
    }

    /**
     * @dataProvider dataDetect
     * @return void
     */
    public function testDetect($url, $action, $params)
    {
        $crawler = AbstractSourceDetector::generateCrawler($url);

        $result = SourceDetector::detect($crawler);
        self::assertInstanceOf(DetectorResult::class, $result);
        self::assertSame($action, $result->getAction());
        self::assertSame($params, $result->getParams());
    }
}
