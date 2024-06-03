<?php

namespace Sportic\Omniresult\RaceResults\RequestDetectors;

use Nip\Utility\Str;
use Sportic\Omniresult\Common\RequestDetector\Detectors\AbstractSourceDetector;

/**
 * Class SourceDetector
 * @package Sportic\Omniresult\Common\Tests\Fixtures\FakeTimer\RequestDetectors
 */
class SourceDetector extends AbstractSourceDetector
{

    /**
     * @inheritDoc
     */
    public function doInvestigation()
    {
        parent::doInvestigation();
        $tags = $this->crawler->filter('script:contains("new RRPublish")');
        foreach ($tags as $tag) {
            $content = $tag->textContent;
            $this->detectFromJavascriptTag($content);
        }
    }

    /**
     * @param $content
     */
    protected function detectFromJavascriptTag($content)
    {
//        <!--    var rrp=new RRPublish(document.getElementById("divRRPublish"), 122816, "results");    -->
//        <!--    var rrp=new RRPublish(document.getElementById("divRRPublish_results"), 293308, "results");    -->
        $content = str_replace("\n", '', $content);
        $search = 'getElementById("divRRPublish';
        $pos = strpos($content, $search);
        if ($pos === false) {
            return;
        }
        $content = Str::after($content, $search);
        $content = Str::after($content, '),');
        $content = Str::before($content, ',');
        $idEvent = intval($content);
        $this->getResult()->setValid(true);
        $this->getResult()->setAction('event');
        $this->getResult()->setParams(['eventid' => $idEvent]);
    }
}
