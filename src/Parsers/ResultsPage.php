<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Parsers;

use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\Common\Models\Race;
use Sportic\Omniresult\Common\Models\Result;
use Sportic\Omniresult\RaceResults\Helper;
use Sportic\Omniresult\RaceResults\Parsers\Traits\HasJsonConfigTrait;
use Sportic\Omniresult\RaceResults\Scrapers\ResultsPage as Scraper;
use Sportic\Omniresult\RaceResults\Utility\CountryFlag;
use Sportic\Omniresult\RaceResults\Utility\Expression;
use Sportic\Omniresult\RaceResults\Utility\RaceCategories;
use Sportic\Omniresult\RaceResults\Utility\Races;

/**
 * Class ResultsPage
 * @package Sportic\Omniresult\RaceResults\Parsers
 *
 * @method Scraper getScraper()
 */
class ResultsPage extends AbstractParser
{
    use HasJsonConfigTrait;

    protected array $header = [];

    protected ?Race $race = null;

    protected function setRace(?Race $race)
    {
        $this->race = $race;
    }

    /**
     * @return array
     */
    protected function generateContent(): array
    {
        $configArray = $this->getConfigArray();

        $headerFields = $configArray['DataFields'] ?: $configArray['list']['Fields'];
        $this->header = $this->parseHeader($headerFields);
        $results = $this->parseResults($configArray['data']);

        return [
            'records' => $results
        ];
    }

    /**
     * @param $items
     * @param $header
     * @return array
     */
    protected function parseResults($list): array
    {
        $return = [];
        $raceName = $this->race ? $this->race->getName() : null;
        foreach ($list as $raceList => $raceData) {
            $raceList = Races::fromDataName($raceList);
            if (
                ($raceName && $raceList !== $raceName)
                && !in_array($raceList, ['f', 'm'])
            ) {
                continue;
            }
            $firstKey = array_key_first($raceData);
            if (is_int($firstKey)) {
                $results = $this->parseResultsInList($raceData, []);
            } else {
                $results = $this->parseResultsInCategory($raceData);
            }
            $return = array_merge($return, $results);
        }
        return $return;
    }

    /**
     * @param $categories
     * @return array
     */
    protected function parseResultsInCategory($categories)
    {
        $return = [];
        foreach ($categories as $listName => $items) {
            $defaultParams = [
                'gender' => $this->parseGenderFromListName($listName),
                'category' => RaceCategories::isListCategory($listName) ? RaceCategories::fromListName($listName) : false,
            ];
            $results = $this->parseResultsInList($items, $defaultParams);
            $return = array_merge($return, $results);
        }
        return $return;
    }

    /**
     * @param $items
     * @param $defaultParams
     * @return array
     */
    protected function parseResultsInList($items, $defaultParams = []): array
    {
        $return = [];
        foreach ($items as $item) {
            foreach ($defaultParams as $key => $value) {
                $item[$key] = $value;
            }
            $return[] = $this->parseResult($item);
        }
        return $return;
    }

    /**
     * @param $listName
     * @return string
     */
    protected function parseGenderFromListName($listName)
    {
        $listName = (string)$listName;
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
     * @param $config
     * @param $header
     * @return Result
     */
    protected function parseResult($config): Result
    {
        $parameters = [];

        foreach (['category', 'gender'] as $field) {
            if (isset($config[$field])) {
                $parameters[$field] = $config[$field];
            }
        }

        foreach ($this->header as $key => $field) {
            if (isset($config[$key])) {
                $parameters[$field] = $config[$key];
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
    protected function parseHeader($config): array
    {
        $fields = [];
        foreach ($config as $key => $configField) {
            $expression = is_string($configField) ? $configField : $configField['Expression'];
            $labelFind = Expression::toResultField($expression);

            if ($labelFind) {
                $fields[$key] = $labelFind;
            }
        }
        return $fields;
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
