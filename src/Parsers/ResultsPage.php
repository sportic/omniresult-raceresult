<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceResults\Helper;
use Sportic\Omniresult\RaceResults\Parsers\Traits\HasJsonConfigTrait;
use Sportic\Omniresult\RaceResults\Scrapers\ResultsPage as Scraper;
use Sportic\Omniresult\RaceResults\Utility\CountryFlag;

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
    protected function parseResult($config, $header): Result
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

        if (isset($parameters['country_flag'])) {
            $parameters['country'] = CountryFlag::from($parameters['country_flag']);
            unset($parameters['country_flag']);
        }
        $paramsId = [
            'eventId' => $this->getScraper()->getEventId(),
            'key' => $this->getScraper()->getKey(),
            'listname' => $this->getParameter('listDetails'),
            'contest' => $this->getScraper()->getContest(),
            'bib' => $parameters['bib'],
        ];

        $parameters['id'] = Helper::encodeResultId($paramsId);

        return new Result($parameters);
    }

    /**
     * @param $config
     * @return array
     */
    protected function parseHeader($config)
    {
        $fields = [];
        foreach ($config as $key => $configField) {
            $fieldMap = self::getLabelMaps();
            $fieldName = $configField['Expression'];
            $labelFind = $fieldMap[$fieldName] ?? null;
            if ($labelFind) {
                $fields[$key] = $labelFind;
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
            'WithStatus([AgeGroupRank.p])' => 'posCategory',
            'AgeGroupRank' => 'posCategory',
            'WithStatus([GenderRankp])' => 'posGender',
            'GenderRank' => 'posGender',
            'BIB' => 'bib',
            'FLNAME' => 'fullNameFL',
            'DisplayName' => 'fullName',
            'GenderMF' => 'gender',
            'AGEGROUPNAMESHORT1' => 'category',
            'AGEGROUPNAME1' => 'category',
            'CLUB' => 'club',
            'NATION.FLAG' => 'country_flag',
            'TIME' => 'time',
            'Chip Time' => 'time',
            'TIMETEXT300' => 'time',
            'GunTime' => 'time_gross',
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
