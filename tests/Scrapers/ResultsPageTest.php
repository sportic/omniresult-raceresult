<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests\Scrapers;

use Sportic\Omniresult\RaceResults\Scrapers\ResultsPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ResultPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
class ResultsPageTest extends AbstractPageTest
{
    public function testGetCrawlerUri()
    {
        $crawler = $this->getCrawler();

        static::assertInstanceOf(Crawler::class, $crawler);

        static::assertSame(
            'https://my.raceresult.com/RRPublish/data/list.php?callback=jQuery&page=results&eventid=122816&key=a615286c279b6fcfaf20b3816f2e2943&listname=Result+Lists%7CGender+Results&contest=1',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $content = $this->scrapeContents([]);

        static::assertStringContainsString('Maria Magdalena Veliscu', $content);
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/default.jsonp', $content);
    }

    public function testGetCrawlerHtmlAgeGroup()
    {
        $content = $this->scrapeContents([
            'eventId' => '126187',
            'key' => 'd184aa4d1b70c5f81cb4422e09088906',
            'contest' => '2',
            'listname' => 'Result Lists|Age Group Results'
        ]);

        static::assertStringContainsString('Ioana Ani', $content);
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/default-agegroup.jsonp', $content);
    }

    protected function generateScraperDefaultParams(): array
    {
        return [
            'eventId' => '122816',
            'key' => 'a615286c279b6fcfaf20b3816f2e2943',
            'contest' => '1',
            'listname' => 'Result Lists|Gender Results'
        ];
    }

    protected function generateScraperClass() : string
    {
        return ResultsPage::class;
    }
}
