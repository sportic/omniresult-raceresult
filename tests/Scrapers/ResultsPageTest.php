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
            'https://my.raceresult.com/122816/RRPublish/data/list?callback=jQuery&page=results&key=a615286c279b6fcfaf20b3816f2e2943&listname=Result+Lists%7CGender+Results&contest=1',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $content = $this->scrapeContents([]);

        static::assertStringContainsString('Maria Magdalena Veliscu', $content);
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/default.jsonp', $content);
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

    public function testGetCrawlerHtmlNoCategories()
    {
        $content = $this->scrapeContents([
            'eventId' => '325564',
            'key' => '37ee7f73f83a033e444029cbc1e3951b',
            'contest' => '5',
            'listname' => 'Lists|Finisher List Cros 3k YoPro'
        ]);

        static::assertStringContainsString('Luminita Badea', $content);
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/default-nocategories.jsonp', $content);
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
