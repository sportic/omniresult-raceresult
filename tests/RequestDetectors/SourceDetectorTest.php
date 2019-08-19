<?php

namespace Sportic\Omniresult\RaceResults\Tests\RequestDetectors;

use Sportic\Omniresult\Common\RequestDetector\DetectorResult;
use Sportic\Omniresult\RaceResults\RequestDetectors\SourceDetector;
use Sportic\Omniresult\RaceResults\Tests\AbstractTest;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SourceDetectorTest
 * @package Sportic\Omniresult\RaceResults\Tests\RequestDetectors
 */
class SourceDetectorTest extends AbstractTest
{
    public function testDetect()
    {
        $crawler = new Crawler(null, 'https://my-run.ro/wizz-air-cluj-napoca-marathon-2019-rezultate/');
        $crawler->addContent(
            file_get_contents(
                TEST_FIXTURE_PATH . '/RequestDetectors/withTags.html'
            ),
            'text/html;charset=utf-8'
        );

        $result = SourceDetector::detect($crawler);
        self::assertInstanceOf(DetectorResult::class, $result);
        self::assertSame('event', $result->getAction());
        self::assertSame(['eventid' => 122816], $result->getParams());
    }
}
