<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Scrapers;

use Sportic\Omniresult\RaceResults\Parsers\EventPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\Endu\Scrapers
 *
 * @method Parser execute()
 */
class EventPage extends AbstractScraper
{
    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->getParameter('eventId');
    }

    /**
     * @throws \Sportic\Omniresult\Common\Exception\InvalidRequestException
     */
    protected function doCallValidation()
    {
        $this->validate('eventId');
    }

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

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        // https://my.raceresult.com/122816/RRPublish/data/config?lang=en&page=results&noVisitor=1&v=1
        return $this->getCrawlerUriHost()
            . '/' . $this->getEventId()
            . '/RRPublish/data/config?lang=en&page=results&noVisitor=1&v=1';
    }
}
