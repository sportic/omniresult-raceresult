<?php

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\ParentListContent;
use Sportic\Omniresult\Common\Models\Event;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\RaceResults\Parsers\Traits\HasJsonConfigTrait;

/**
 * Class EventPage
 * @package Sportic\Omniresult\Endu\Parsers
 */
class EventPage extends AbstractParser
{
    use HasJsonConfigTrait;

    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $configArray = $this->getConfigArray();
        $races = $this->parseRaces($configArray);

        $params = [
            'record' => $this->parseEvent($configArray),
            'records' => $this->parseRaces($configArray)
        ];

        return $params;
    }

    /**
     * @param $config
     * @return Event
     */
    public function parseEvent($config)
    {
        $event = new Event([
            'id' => $config['key'],
            'name' => $config['eventname']
        ]);
        return $event;
    }

    /**
     * @param $config
     * @return Race[]
     */
    public function parseRaces($config)
    {
        $racesArray = $config['contests'];
        $listArray = $config['lists'];
        $races = [];
        foreach ($listArray as $listItem) {
            $this->parseList($races, $listItem, $racesArray);
        }
        return $races;
    }

    /**
     * @param $races
     * @param $listItem
     * @param $racesArray
     */
    protected function parseList(&$races, $listItem, $racesArray)
    {
        if (!isset($races[$listItem['Contest']])) {
            $races[$listItem['Contest']] = new Race([
                'id' => $listItem['Contest'],
                'name' => $racesArray[$listItem['Contest']]
            ]);
        }
        $races[$listItem['Contest']]->lists[] = $listItem;
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ParentListContent::class;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Race::class;
    }
}
