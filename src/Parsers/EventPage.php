<?php

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Race;

/**
 * Class EventPage
 * @package Sportic\Omniresult\Endu\Parsers
 */
class EventPage extends AbstractParser
{
    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $configArray = $this->getConfigArray();
        $races = $this->parseRaces($configArray);

        $params = [
            'records' => $races
        ];

        return $params;
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

    /**
     * @return array
     */
    protected function getConfigArray()
    {
        $configHtml = $this->getConfigString();

        $data = json_decode($configHtml, true);
        return $data;
    }

    /**
     * @return mixed|string
     */
    protected function getConfigString()
    {
        $string = $this->getResponse()->getContent();
        $string = str_replace('jQuery(', '', $string);
        $string = str_replace(');', '', $string);

        return $string;
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return ListContent::class;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Race::class;
    }
}
