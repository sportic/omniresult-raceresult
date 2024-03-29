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
        $this->race= $race;
    }

    /**
     * @return array
     */
    protected function generateContent(): array
    {
        $configArray = $this->getConfigArray();

        $this->header = $this->parseHeader($configArray['list']['Fields']);
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
        foreach ($list as $raceList => $categories) {
            $raceList = Races::fromDataName($raceList);
            if ($raceName && $raceList !== $raceName) {
                continue;
            }
            $this->parseResultsInCategory($return, $categories);
        }
        return $return;
    }

    /**
     * @param $return
     * @param $categories
     * @return void
     */
    protected function parseResultsInCategory(&$return, $categories)
    {
        foreach ($categories as $listName => $items) {
            $gender = $this->parseGenderFromListName($listName);
            $category = RaceCategories::isListCategory($listName) ? RaceCategories::fromListName($listName) : false;
            foreach ($items as $item) {
                $item['gender'] = $gender;
                if ($category) {
                    $item['category'] = $category;
                }
                $return[] = $this->parseResult($item);
            }
        }
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
    protected function parseHeader($config): array
    {
        $fields = [];
        foreach ($config as $key => $configField) {
            $labelFind = Expression::toResultField($configField['Expression']);
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
