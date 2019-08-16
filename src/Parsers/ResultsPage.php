<?php

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceResults\Helper;
use Sportic\Omniresult\RaceResults\Parsers\Traits\HasJsonConfigTrait;
use Sportic\Omniresult\RaceResults\Scrapers\ResultsPage as Scraper;

/**
 * Class ResultsPage
 * @package Sportic\Omniresult\RaceResults\Parsers
 *
 * @method Scraper getScraper()
 */
class ResultsPage extends AbstractParser
{
    use HasJsonConfigTrait;

    protected $header = null;

    /**
     * @return array
     */
    protected function generateContent()
    {
        $configArray = $this->getConfigArray();
        $header = $this->parseHeader($configArray['list']['Fields']);
        $results = $this->parseResults($configArray['data'], $header);

        $params = [
            'records' => $results
        ];
        return $params;
    }

    /**
     * @param $items
     * @param $header
     * @return array
     */
    protected function parseResults($list, $header)
    {
        $return = [];
        foreach ($list as $race => $categories) {
            foreach ($categories as $listName => $items) {
                $gender = $this->parseGenderFromListName($listName);
                $category = Helper::isListCategory($listName) ? $this->parseCategoryFromListName($listName) : false;
                foreach ($items as $item) {
                    $item['gender'] = $gender;
                    if ($category) {
                        $item['category'] = $category;
                    }
                    $return[] = $this->parseResult($item, $header);
                }
            }
        }
        return $return;
    }

    /**
     * @param $listName
     * @return string
     */
    protected function parseGenderFromListName($listName)
    {
        $listName = strtolower($listName);
        if (strpos($listName, 'female')) {
            return 'female';
        }
        if (strpos($listName, 'male')) {
            return 'male';
        }
        return '';
    }

    /**
     * @param $listName
     * @return string
     */
    protected function parseCategoryFromListName($listName)
    {
        $numbers = range(1, 9);
        foreach ($numbers as $digit) {
            $listName = str_replace('#' . $digit . '_', '', $listName);
        }
        return $listName;
    }

    /**
     * @param $config
     * @param $header
     * @return Result
     */
    protected function parseResult($config, $header)
    {
        $parameters = [];

        foreach (['category', 'gender'] as $field) {
            if (isset($config[$field])) {
                $parameters[$field] = $config[$field];
            }
        }

        foreach ($header as $key => $field) {
            if (isset($config[$key + 1])) {
                $parameters[$field] = $config[$key + 1];
            }
        }
        if (isset($parameters['posCategory'])) {
            $parameters['posCategory'] = intval($parameters['posCategory']);
        }
        if (isset($parameters['posGender'])) {
            $parameters['posGender'] = intval($parameters['posGender']);
        }


        $paramsId = [
            'eventId' => $this->getScraper()->getEventId(),
            'key' => $this->getScraper()->getKey(),
            'listname' => $this->getParameter('listDetails'),
            'contest' => $this->getScraper()->getContest(),
            'bib' => $parameters['bib'],
        ];

        $parameters['id'] = Helper::encodeResultId($paramsId);

        $result = new Result($parameters);
        return $result;
    }

    /**
     * @param $config
     * @return array
     */
    protected function parseHeader($config)
    {
        $fields = [];
        foreach ($config as $i => $configField) {
            $fieldMap = self::getLabelMaps();
            $fieldName = $configField['Expression'];
            $labelFind = isset($fieldMap[$fieldName]) ? $fieldMap[$fieldName] : null;
            if ($labelFind) {
                $fields[$i] = $labelFind;
            }
        }
        return $fields;
    }

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'WithStatus([AgeGroupRankp])' => 'posCategory',
            'AgeGroupRank' => 'posCategory',
            'WithStatus([GenderRankp])' => 'posGender',
            'GenderRank' => 'posGender',
            'BIB' => 'bib',
            'DisplayName' => 'fullName',
            'GenderMF' => 'gender',
            'AGEGROUPNAMESHORT1' => 'category',
            'Chip Time' => 'time',
            'TIMETEXT300' => 'time',
            'TIMETEXT' => 'time_gross',
        ];
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
        return Result::class;
    }
}
