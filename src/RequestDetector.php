<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults;

use Sportic\Omniresult\Common\RequestDetector\Detectors\AbstractUrlDetector;

/**
 * Class RequestDetector
 * @package Sportic\Omniresult\RaceResults
 */
class RequestDetector extends AbstractUrlDetector
{
    protected $path = null;
    protected $pathParts = null;

    /**
     * @inheritdoc
     */
    protected function isValidRequest()
    {
        if (in_array(
            $this->getUrlComponent('host'),
            [
                'raceresult.com',
                'my.raceresult.com',
                'my1.raceresult.com',
                'my2.raceresult.com',
                'my3.raceresult.com',
                'my4.raceresult.com',
                'my5.raceresult.com'
            ]
        )) {
            return true;
        }
        return parent::isValidRequest();
    }

    /**
     * @return string
     */
    protected function detectAction()
    {
        $path = $this->getPath();

        if ($path == 'rrpublish/data/config.php') {
            return 'event';
        }
        if ($path == 'rrpublish/data/list.php') {
            return 'results';
        }
        return '';
    }

    /**
     * @inheritdoc
     */
    protected function detectParams()
    {
        $queryString = $this->getUrlComponent('query');
        parse_str($queryString, $query);

        $return = [];
        foreach (['eventid', 'contest', 'listname'] as $key) {
            if (isset($query[$key])) {
                $return[$key] = $query[$key];
            }
        }

        return $return;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        if ($this->path === null) {
            $this->path = strtolower($this->getUrlComponent('path'));
            $this->path = trim($this->path, '/');
        }
        return $this->path;
    }

    /**
     * @return array
     */
    public function getPathParts(): array
    {
        if ($this->pathParts === null) {
            $this->detectUrlPathParts();
        }
        return $this->pathParts;
    }

    protected function detectUrlPathParts()
    {
        $this->pathParts = explode('/', $this->getPath());
    }
}
