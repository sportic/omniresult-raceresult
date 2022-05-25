<?php
declare(strict_types=1);

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
        foreach ($racesArray as $idRace => $name) {
            $races[$idRace] = new Race([
                'id' => $idRace,
                'name' => $name
            ]);
        }
        foreach ($listArray as $listItem) {
            $this->parseList($races, $listItem, $racesArray);
        }
        return $races;
    }

    /**
     * @param Race[] $races
     * @param $listItem
     * @param $racesArray
     */
    protected function parseList(&$races, $listItem, $racesArray)
    {
        $listContest = $listItem['Contest'];
        if ($listContest == 0) {
            foreach ($races as $idRace => $race) {
                $races[$idRace]->lists[$listItem['Name']] = $listItem;
            }
            return;
        }
        if ($listContest < 1 && count($racesArray) == 1) {
            $listContest = array_key_first($racesArray);
        }
        if (!isset($racesArray[$listContest])) {
            return;
        }
        if (!isset($races[$listContest])) {
            $races[$listContest] = new Race([
                'id' => $listItem['Contest'],
                'name' => $racesArray[$listContest]
            ]);
        }
        $races[$listContest]->lists[$listItem['Name']] = $listItem;
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
