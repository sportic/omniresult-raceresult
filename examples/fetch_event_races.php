<?php

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '122816'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->event($parameters);
$resultsData   = $resultsParser->getContent();

var_dump($resultsData);
