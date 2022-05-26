<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests\Parsers;

use Sportic\Omniresult\Common\Models\Event;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\RaceResults\Scrapers\EventPage as PageScraper;
use Sportic\Omniresult\RaceResults\Parsers\EventPage as PageParser;

/**
 * Class EventPageTest
 */
class EventPageTest extends AbstractPageTest
{

    public function testGenerateContentDefault()
    {
        $parametersParsed = $this->generateParsedParameters('default');

        /** @var Event::class $record */
        $record = $parametersParsed->getRecord();
        self::assertInstanceOf(Event::class, $record);
        self::assertSame('a615286c279b6fcfaf20b3816f2e2943', $record->getId());
        self::assertSame('Wizz Air Cluj-Napoca Marathon 2019', $record->getName());

        /** @var Race[] $records */
        $records = $parametersParsed->getRecords();
        self::assertCount(4, $records);

    }

    public function test_single_list()
    {
        $parametersParsed = $this->generateParsedParameters('single_list');

        /** @var Event::class $record */
        $record = $parametersParsed->getRecord();
        self::assertInstanceOf(Event::class, $record);
        self::assertSame('a64022d4fedfbbb03f4c8fe4efd7e5b5', $record->getId());
        self::assertSame('Crosul Companiilor', $record->getName());

        /** @var Race[] $records */
        $records = $parametersParsed->getRecords();
        self::assertCount(1, $records);
    }

    public function test_multiple_race_in_same_list()
    {
        $parametersParsed = $this->generateParsedParameters('multiple_race_in_same_list');

        /** @var Event::class $record */
        $record = $parametersParsed->getRecord();
        self::assertInstanceOf(Event::class, $record);

        /** @var Race[] $records */
        $records = $parametersParsed->getRecords();
        self::assertCount(2, $records);

        $firstRace = current($records);
        self::assertInstanceOf(Race::class, $firstRace);
        self::assertCount(3, $firstRace->lists);
    }

    /**
     * @param $path
     * @return mixed
     */
    protected function generateParsedParameters($path)
    {
        return static::initParserFromFixturesJsonp(
            new PageParser(),
            (new PageScraper()),
            'EventPage/' . $path
        );
    }

    /**
     * @inheritdoc
     */
    protected static function getNewParser()
    {
        return new PageParser();
    }
}
