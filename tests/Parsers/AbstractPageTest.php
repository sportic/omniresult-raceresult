<?php

namespace Sportic\Omniresult\RaceResults\Tests\Parsers;

use Sportic\Omniresult\Common\Content\GenericContent;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Content\RecordContent;
use Sportic\Omniresult\RaceResults\Parsers\AbstractParser;
use Sportic\Omniresult\RaceResults\Scrapers\AbstractScraper;
use Sportic\Omniresult\RaceResults\Parsers\EventPage as EventPageParser;
use Sportic\Omniresult\RaceResults\Tests\AbstractTest;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
abstract class AbstractPageTest extends AbstractTest
{
    protected static $parameters;

    /**
     * @var GenericContent|ListContent|RecordContent
     */
    protected static $parametersParsed;

    /**
     * @param AbstractParser $parser
     * @param AbstractScraper $scrapper
     * @param $fixturePath
     * @return mixed
     */
    public static function initParserFromFixtures($parser, $scrapper, $fixturePath)
    {
        $crawler = new Crawler(null, $scrapper->getCrawlerUri());
        $crawler->addContent(
            file_get_contents(
                TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.html'
            ),
            'text/html;charset=utf-8'
        );

        $parser->setScraper($scrapper);
        $parser->setCrawler($crawler);

        $parametersParsed = $parser->getContent();

//        file_put_contents(
//            TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.serialized',
//            serialize($parser->getContent()->all())
//        );

        return $parametersParsed;
    }


    /**
     * @param \Sportic\Omniresult\RaceResults\Parsers\AbstractParser $parser
     * @param \Sportic\Omniresult\RaceResults\Scrapers\AbstractScraper $scrapper
     * @param $fixturePath
     * @return mixed
     */
    public static function initParserFromFixturesJsonp($parser, $scrapper, $fixturePath)
    {
        $response = new Response(
            file_get_contents(
                TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.jsonp'
            )
        );
        $parser->setScraper($scrapper);
        $parser->initialize(['response' => $response]);

        $parametersParsed = $parser->getContent();

        return $parametersParsed;
    }
    /**
     * @param $fixturePath
     * @return mixed
     */
    public static function getParametersFixtures($fixturePath)
    {
        return unserialize(
            file_get_contents(TEST_FIXTURE_PATH . DS . 'Parsers' . DS . $fixturePath . '.serialized')
        );
    }
}
