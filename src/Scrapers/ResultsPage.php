<?php
declare(strict_types=1);

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
    protected $racesData = null;

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
     * @param string $name
     * @return void
     */
    public function setListName(string $name)
    {
        $this->setParameter('listname', urldecode($name));
    }

    /**
     * @return string
     */
    public function getCrawlerUri()
    {
        $contest = $this->getContest();

        $races = $this->racesData();
        $list = $races[$contest]->lists[$this->getListName()];

        return $this->getCrawlerUriHost()
            . '/' . $this->getEventId()
            . '/RRPublish/data/list?callback=jQuery&page=results'
            . '&key=' . $this->getKey()
            . '&listname=' . urlencode($this->getListName())
            . '&contest=' . $list['Contest'];
    }


    /**
     * @inheritdoc
     */
    protected function generateParserData()
    {
        $data = parent::generateParserData();

        $currentListName = $this->getListName();
        $contest = $this->getContest();

        $races = $this->racesData();

        $data['race'] = $races[$contest];
        $data['listDetails'] = $data['race']->lists[$currentListName]['Details'];

        return $data;
    }

    /**
     * @return mixed
     */
    protected function racesData()
    {
        if ($this->racesData === null) {
            $dataEvent = $this->getParameter('raceClient')->event(['eventId' => $this->getEventId()])->getContent();
            $this->racesData = $dataEvent->getRecords();
        }
        return $this->racesData;
    }
}
