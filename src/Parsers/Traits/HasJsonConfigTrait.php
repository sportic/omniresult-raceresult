<?php

namespace Sportic\Omniresult\RaceResults\Parsers\Traits;

/**
 * Trait HasJsonConfigTrait
 * @package Sportic\Omniresult\RaceResults\Parsers\Traits
 */
trait HasJsonConfigTrait
{
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
}
