<?php

namespace Sportic\Omniresult\RaceResults\Scrapers;

use ByTIC\GouttePhantomJs\Clients\ClientFactory;
use Goutte\Client;

/**
 * Class AbstractScraper
 * @package Sportic\Omniresult\Trackmyrace\Scrapers
 */
abstract class AbstractScraper extends \Sportic\Omniresult\Common\Scrapers\AbstractScraper
{
    /**
     * @inheritdoc
     */
    protected function generateCrawler()
    {
        $client = $this->getClient();
        $crawler = $client->request(
            'GET',
            $this->getCrawlerUri()
        );

        return $crawler;
    }

    /**
     * @return array
     */
    protected function generateParserData()
    {
        $this->getRequest();

        return [
            'response' => $this->getClient()->getResponse(),
        ];
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @return Client
     */
    protected function generateClient()
    {
        return ClientFactory::getGoutteClient();
    }

    /**
     * @return string
     */
    abstract public function getCrawlerUri();/** @noinspection PhpMethodNamingConventionInspection */

    /**
     * @return string
     */
    protected function getCrawlerUriHost()
    {
        return 'https://my.raceresult.com';
    }
}
