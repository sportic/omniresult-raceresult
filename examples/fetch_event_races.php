<?php

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '122816'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->event($parameters);
$resultsData = $resultsParser->getContent();
$races = $resultsData->getRecords();

var_dump($resultsData->getRecord());

foreach ($races as $id => $race) {
    var_dump($race);
}
