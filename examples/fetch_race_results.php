<?php

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '122816',
    'key' => 'a615286c279b6fcfaf20b3816f2e2943',
    'contest' => '1',
    'listname' => 'Result Lists|Gender Results'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->results($parameters);
$resultsData = $resultsParser->getContent();

var_dump($resultsData->getRecords());
