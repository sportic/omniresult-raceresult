<?php

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\RecordContent;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\Common\Models\Split;
use Sportic\Omniresult\RaceResults\Parsers\Traits\HasJsonConfigTrait;

/**
 * Class ResultPage
 * @package Sportic\Omniresult\RaceResults\Parsers
 */
class ResultPage extends AbstractParser
{
    use HasJsonConfigTrait;

    protected $returnContent = [];

    /**
     * @return array
     */
    protected function generateContent()
    {
        $configArray = $this->getConfigArray();
        $data = $configArray['data'][0];

        $header = $this->parseHeader($configArray['list']['Fields']);
        $result = $this->parseResult($data, $header);

//        $this->parseSplits($result, $configArray['records']);

        $params = [
            'record' => $result
        ];

        return $params;
    }

    /**
     * @param $config
     * @return Result
     */
    protected function parseResult($config, $header)
    {
        $parameters = [];
        $splits = [];
        foreach ($header as $key => $field) {
            if (isset($config[$key + 1])) {
                $value = $config[$key + 1];
                if (strpos($field, 'SPLIT#') === 0) {
                    $splits[str_replace('SPLIT#', '', $field)] = $value;
                } else {
                    $parameters[$field] = $value;
                }
            }
        }
        $result = new Result($parameters);
        if (count($splits)) {
            $this->parseSplits($result, $splits);
        }
        return $result;
    }

    /**
     * @param Result $result
     * @param $splits
     */
    protected function parseSplits($result, $splits)
    {
        foreach ($splits as $name => $time) {
            $params = [
                'name' => $name,
                'timeFromStart' => $time,
            ];
            $split = new Split($params);
            $result->getSplits()->add($split);
        }
    }

    /**
     * @param $config
     * @return array
     */
    protected function parseHeader($config)
    {
        $fields = [];
        $lastField = false;
        foreach ($config as $i => $configField) {
            $fieldMap = self::getLabelMaps();
            $fieldName = $configField['Expression'];
            $labelFind = isset($fieldMap[$fieldName]) ? $fieldMap[$fieldName] : null;
            if ($labelFind) {
                $fields[$i] = $labelFind;
            } elseif (strpos($fieldName, 'AfterLap') === 0) {
                $legName = 'SPLIT#' . $lastField;
                $fields[$i] = $legName;
            }
            $lastField = $fieldName;
        }
        return $fields;
    }

    /**
     * @return array
     */
    public static function getLabelMaps()
    {
        return [
            'FLNAME' => 'fullName',
            'Gun Time' => 'time_gross',
            'Chip Time' => 'time',
            'AgeGroupRank' => 'posCategory',
            'GenderRank' => 'posGender',
            'OverallRank' => 'posGen',
        ];
    }


    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    protected function getContentClassName()
    {
        return RecordContent::class;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     */
    public function getModelClassName()
    {
        return Result::class;
    }
}
