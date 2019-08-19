<?php

namespace Sportic\Omniresult\RaceResults\RequestDetectors;

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
        $content = str_replace("\n", '', $content);
        $pos = strpos($content, 'getElementById("divRRPublish"),');
        if ($pos !== false) {
            $content = substr($content, $pos+31);
            $pos = strpos($content, ');');
            $content = substr($content, 0, $pos);
            $idEvent = intval($content);
            $this->getResult()->setValid(true);
            $this->getResult()->setAction('event');
            $this->getResult()->setParams(['eventid' => $idEvent]);
        }
    }
}
