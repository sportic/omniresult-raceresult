<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests\Scrapers;

use JetBrains\PhpStorm\ArrayShape;
use Sportic\Omniresult\RaceResults\RaceResultsClient;
use Sportic\Omniresult\RaceResults\Scrapers\EventPage;
use Sportic\Omniresult\RaceResults\Scrapers\ResultsPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ResultPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
class EventPageTest extends AbstractPageTest
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'https://my.raceresult.com/RRPublish/data/config.php?callback=jQuery&page=results&eventid=122816',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $content = $this->scrapeContents();

        static::assertStringContainsString('Wizz Air Cluj-Napoca Marathon 2019', $content);
        static::assertStringContainsString('a615286c279b6fcfaf20b3816f2e2943', $content);
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/EventPage/default.jsonp', $content);
    }

    public function test_scrape_single_list()
    {
        $content = $this->scrapeContents(['eventId' => "182141"]);

        static::assertStringContainsString('a64022d4fedfbbb03f4c8fe4efd7e5b5', $content);
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/EventPage/single_list.jsonp', $content);
    }

    #[ArrayShape(['eventId' => "string"])]
    protected function generateScraperDefaultParams(): array
    {
        return [
            'eventId' => '122816'
        ];
    }

    protected function generateScraperClass(): string
    {
        return EventPage::class;
    }
}
