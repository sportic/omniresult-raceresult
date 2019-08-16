<?php

namespace Sportic\Omniresult\RaceResults\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceResults\Scrapers\ResultsPage as PageScraper;
use Sportic\Omniresult\RaceResults\Parsers\ResultsPage as PageParser;

/**
 * Class ResultsPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
class ResultsPageTest extends AbstractPageTest
{
    public function testGenerateContentDefault()
    {
        $parametersParsed = static::initParserFromFixturesJsonp(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/default'
        );

        /** @var Result $record */
        $records = $parametersParsed->getRecords();
        self::assertCount(561, $records);

        $record = $records[0];

        self::assertInstanceOf(Result::class, $record);
        self::assertEquals('Emily Jemutai CHERUIYOT', $record->getFullName());

        self::assertEquals('F 35-44', $record->getCategory());
        self::assertEquals('2:49:11.00', $record->getTime());

        self::assertEquals('1', $record->getPosGender());
    }

    public function testGenerateContentAgeGroup()
    {
        $parametersParsed = static::initParserFromFixturesJsonp(
            new PageParser(),
            (new PageScraper()),
            'ResultsPage/default-agegroup'
        );

        /** @var Result $record */
        $records = $parametersParsed->getRecords();
        self::assertCount(335, $records);

        $record = $records[0];

        self::assertInstanceOf(Result::class, $record);
        self::assertEquals('TIMEA JÓZSA', $record->getFullName());

        self::assertEquals('Female U35', $record->getCategory());
        self::assertEquals('f', $record->getGender());

        self::assertEquals('46:46.80', $record->getTime());
        self::assertEquals('1', $record->getPosCategory());
    }

    /**
     * @inheritdoc
     */
    protected static function getNewScraper($params)
    {
        $default = [
            'eventId' => '122816',
            'key' => 'a615286c279b6fcfaf20b3816f2e2943',
            'contest' => '1',
            'listname' => 'Result Lists|Gender Results'
        ];
        $params = count($params) ? $params : $default;

        $scraper = new PageScraper();
        $scraper->initialize($params);
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
