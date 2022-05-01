<?php
declare(strict_types=1);

namespace Sportic\Omniresult\RaceResults\Tests\Scrapers;

use Sportic\Omniresult\RaceResults\Scrapers\ResultPage;

/**
 * Class ResultPageTest
 * @package Sportic\Omniresult\RaceResults\Tests\Scrapers
 */
class ResultPageTest extends AbstractPageTest
{

    public function testInitializeFromId()
    {
        $scraper = $this->generateNewScraper([
            'id' => 'YTo1OntzOjc6ImV2ZW50SWQiO3M6NjoiMTIyODE2IjtzOjM6ImtleSI7czozMjoiYTYxNTI4NmMyNzliNmZjZmFmMjBiMzgxNmYyZTI5NDMiO3M6ODoibGlzdG5hbWUiO3M6NDE6IlJlc3VsdCBMaXN0c3xMYXAgRGV0YWlscyBNYXJhdGhvbiArIFJlbGF5IjtzOjc6ImNvbnRlc3QiO3M6MToiMSI7czozOiJiaWIiO2k6MjEwO30%3D'
        ]);

        self::assertEquals('122816', $scraper->getEventId());
        self::assertEquals('a615286c279b6fcfaf20b3816f2e2943', $scraper->getKey());
        self::assertEquals('1', $scraper->getContest());
        self::assertEquals('Result Lists|Lap Details Marathon + Relay', $scraper->getListName());
        self::assertEquals('210', $scraper->getBib());
    }

    protected function generateScraperClass(): string
    {
        return ResultPage::class;
    }
}
