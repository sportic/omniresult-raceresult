<?php

namespace Sportic\Omniresult\RaceResults;

use Sportic\Omniresult\Common\RequestDetector\HasDetectorTrait;
use Sportic\Omniresult\Common\TimingClient;
use Sportic\Omniresult\RaceResults\Scrapers\EventPage;
use Sportic\Omniresult\Trackmyrace\Scrapers\ResultPage;
use Sportic\Omniresult\Trackmyrace\Scrapers\ResultsPage;

/**
 * Class RaceResultsClient
 * @package Sportic\Omniresult\RaceResults
 */
class RaceResultsClient extends TimingClient
{
    use HasDetectorTrait;


    /**
     * @param $parameters
     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\EventPage
     */
    public function event($parameters)
    {
        return $this->executeScrapper(EventPage::class, $parameters);
    }

//    /**
//     * @param $parameters
//     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultsPage
//     */
//    public function results($parameters)
//    {
//        return $this->executeScrapper(ResultsPage::class, $parameters);
//    }
//
//    /**
//     * @param $parameters
//     * @return \Sportic\Omniresult\Common\Parsers\AbstractParser|Parsers\ResultPage
//     */
//    public function result($parameters)
//    {
//        return $this->executeScrapper(ResultPage::class, $parameters);
//    }
}
