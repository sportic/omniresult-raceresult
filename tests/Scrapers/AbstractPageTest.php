<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests\Scrapers;

use Sportic\Omniresult\Common\Content\GenericContent;
use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Content\RecordContent;
use Sportic\Omniresult\RaceResults\Parsers\AbstractParser;
use Sportic\Omniresult\RaceResults\RaceResultsClient;
use Sportic\Omniresult\RaceResults\Scrapers\AbstractScraper;
use Sportic\Omniresult\RaceResults\Parsers\EventPage as EventPageParser;
use Sportic\Omniresult\RaceResults\Scrapers\EventPage;
use Sportic\Omniresult\RaceResults\Tests\AbstractTest;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
abstract class AbstractPageTest extends AbstractTest
{

    /**
     * @param array $parameters
     * @return string
     */
    protected function scrapeContents(array $parameters = [])
    {
        $scrapper = $this->generateScraper($parameters);
        static::assertInstanceOf($this->generateScraperClass(), $scrapper);

        $scrapper->execute();
//        echo $scrapper->getClient()->getHistory()->current()->getUri();

        return $scrapper->getClient()->getResponse()->getContent();
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
     * @return AbstractScraper
     */
    protected function generateScraper($parameters = [])
    {
        $params = count($parameters) ? $parameters : $this->generateScraperDefaultParams();
        $params['raceClient'] = new RaceResultsClient();
        $scraper = $this->generateNewScraper($params);
        return $scraper;
    }

    /**
     * @return AbstractScraper
     */
    protected function generateNewScraper($parameters = [])
    {
        $scraperClass = $this->generateScraperClass();
        $scraper = new $scraperClass();
        $scraper->initialize($parameters);
        return $scraper;
    }

    protected function generateScraperDefaultParams(): array
    {
        return [];
    }

    abstract protected function generateScraperClass() : string;
}
