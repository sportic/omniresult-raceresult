<?php

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceResults\Parsers\Traits\HasJsonConfigTrait;

/**
 * Class ResultsPage
 * @package Sportic\Omniresult\RaceResults\Parsers
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
            foreach ($categories as $items) {
                foreach ($items as $item) {
                    $return[] = $this->parseResult($item, $header);
                }
            }
        }
        return $return;
    }

    /**
     * @param $config
     * @param $header
     * @return Result
     */
    protected function parseResult($config, $header)
    {
        $parameters = [];
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
            'WithStatus([GenderRankp])' => 'posGender',
            'GenderRank' => 'posGender',
            'BIB' => 'bib',
            'DisplayName' => 'fullName',
            'GenderMF' => 'gender',
            'AGEGROUPNAMESHORT1' => 'category',
            'Chip Time' => 'time',
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
