<?php
declare(strict_types=1);

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '217070'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->event($parameters);
$resultsData = $resultsParser->getContent();
$races = $resultsData->getRecords();

var_dump($resultsData->getRecord());

foreach ($races as $id => $race) {
    var_dump($race);
}
