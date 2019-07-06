<?php

namespace Sportic\Omniresult\RaceResults\Tests;

use Sportic\Omniresult\RaceResults\RequestDetector;

/**
 * Class RequestDetectorTest
 * @package Sportic\Omniresult\RaceResults\Tests
 */
class RequestDetectorTest extends AbstractTest
{
    /**
     * @param $url
     * @param $valid
     * @param $action
     * @param $params
     * @dataProvider detectProvider
     */
    public function testDetect($url, $valid, $action, $params)
    {
        $result = RequestDetector::detect($url);

        self::assertSame($valid, $result->isValid());
        self::assertSame($action, $result->getAction());
        self::assertSame($params, $result->getParams());
    }

    /**
     * @return array
     */
    public function detectProvider()
    {
        return [
            [
                'https://my1.raceresult.com/RRPublish/data/config.php?callback=jQuery17101269317693762544_1562421524734&eventid=122816&page=results&_=1562421524899',
                true,
                'event',
                ['eventid' => '122816']
            ],
            [
                'https://my5.raceresult.com/RRPublish/data/list.php?callback=jQuery171043649401278624955_1562421070052&eventid=122816&key=a615286c279b6fcfaf20b3816f2e2943&listname=Result+Lists%7CAge+Group+Results&page=results&contest=1&r=all&l=0&_=1562421093804',
                true,
                'results',
                ['eventid' => '122816', 'contest' => '1', 'listname' => 'Result Lists|Age Group Results']
            ]
        ];
    }
}
