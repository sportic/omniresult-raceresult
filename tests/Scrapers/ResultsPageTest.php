<?php

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
            'https://my.raceresult.com/RRPublish/data/list.php?callback=jQuery&page=results&eventid=122816&key=a615286c279b6fcfaf20b3816f2e2943&listname=Result Lists|Gender Results&contest=1',
            $crawler->getUri()
        );
    }

    public function testGetCrawlerHtml()
    {
        $scrapper = $this->generateScraper();

        static::assertInstanceOf(ResultsPage::class, $scrapper);
        $scrapper->execute();
        $content = $scrapper->getClient()->getResponse()->getContent();

        static::assertContains('Maria Magdalena Veliscu', $content);
//        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/default.jsonp', $content);
    }

    public function testGetCrawlerHtmlAgeGroup()
    {
        $params = [
            'eventId' => '126187',
            'key' => 'd184aa4d1b70c5f81cb4422e09088906',
            'contest' => '2',
            'listname' => 'Result Lists|Age Group Results'
        ];

        $scrapper = $this->generateScraper($params);

        static::assertInstanceOf(ResultsPage::class, $scrapper);
        $scrapper->execute();
        $content = $scrapper->getClient()->getResponse()->getContent();

        static::assertContains('Ioana Ani', $content);
        file_put_contents(TEST_FIXTURE_PATH . '/Parsers/ResultsPage/default-agegroup.jsonp', $content);
    }

    /**
     * @param array $parameters
     * @return Crawler
     */
    protected function getCrawler($parameters = [])
    {
        $scraper = $this->generateScraper($parameters);
        return $scraper->getCrawler();
    }

    /**
     * @param array $parameters
     * @return ResultsPage
     */
    protected function generateScraper($parameters = [])
    {
        $default = [
            'eventId' => '122816',
            'key' => 'a615286c279b6fcfaf20b3816f2e2943',
            'contest' => '1',
            'listname' => 'Result Lists|Gender Results'
        ];
        $params = count($parameters) ? $parameters : $default;
        $params['raceClient'] = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
        $scraper = new ResultsPage();
        $scraper->initialize($params);
        return $scraper;
    }
}