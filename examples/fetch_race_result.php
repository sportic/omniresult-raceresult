<?php

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '122816',
    'key' => 'a615286c279b6fcfaf20b3816f2e2943',
    'listname' => 'Result Lists|Lap Details Marathon + Relay',
    'contest' => '1',
    'bib' => '680'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->result($parameters);
$resultsData = $resultsParser->getContent();

var_dump($resultsData->getRecord());
