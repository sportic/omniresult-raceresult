<?php

namespace Sportic\Omniresult\RaceResults\Scrapers;

use Sportic\Omniresult\RaceResults\Parsers\EventPage as Parser;

/**
 * Class CompanyPage
 * @package Sportic\Omniresult\RaceResults\Scrapers
 *
 * @method Parser execute()
 */
class ResultsPage extends AbstractScraper
{
    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->getParameter('eventId');
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->getParameter('key');
    }

    /**
     * @return mixed
     */
    public function getContest()
    {
        return $this->getParameter('contest');
    }

    /**
     * @return mixed
     */
    public function getListName()
    {
        return $this->getParameter('listname');
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        return $this->getCrawlerUriHost()
            . '/RRPublish/data/list.php?callback=jQuery&page=results'
            . '&eventid=' . $this->getEventId()
            . '&key=' . $this->getKey()
            . '&listname=' . $this->getListName()
            . '&contest=' . $this->getContest();
    }


    /**
     * @inheritdoc
     */
    protected function generateParserData()
    {
        $data = parent::generateParserData();

        $currentListName = $this->getListName();
        $contest = $this->getContest();

        $dataEvent = $this->getParameter('raceClient')->event(['eventId' => $this->getEventId()])->getContent();
        $races = $dataEvent ->getRecords();

        $list = $races[$contest]->lists[$currentListName];

        $data['listDetails'] = $list['Details'];

        return $data;
    }
}
