<?php

namespace Sportic\Omniresult\RaceResults\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceResults\Scrapers\ResultPage as PageScraper;
use Sportic\Omniresult\RaceResults\Parsers\ResultPage as PageParser;

/**
 * Class ResultPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
class ResultPageTest extends AbstractPageTest
{
    public function testGenerateResultsBox()
    {
        $parametersParsed = static::initParserFromFixturesJsonp(
            new PageParser(),
            (new PageScraper()),
            'ResultPage/default'
        );

        /** @var Result $record */
        $record = $parametersParsed->getRecord();

        self::assertInstanceOf(Result::class, $record);
        self::assertEquals('Emily Jemutai CHERUIYOT', $record->getFullName());

        self::assertEquals('2:49:11.00', $record->getTime());

        self::assertEquals('1', $record->getPosGender());
        self::assertEquals('1', $record->getPosCategory());

        self::assertCount(4, $record->getSplits());
    }

    /**
     * @inheritdoc
     */
    protected static function getNewScraper()
    {
        $parameters = ['id' => 'cozia-mountain-run-6/individual/-bf626f0882/1281/'];
        $scraper = new PageScraper();
        $scraper->initialize($parameters);
        return $scraper;
    }

    /**
     * @inheritdoc
     */
    protected static function getNewParser()
    {
        return new PageParser();
    }
}
