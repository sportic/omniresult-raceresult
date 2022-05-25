<?php
declare(strict_types=1);

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '202895',
    'key' => '262bb03c932a94f5829e4a3d9103db14',
    'contest' => '1',
    'listname' => 'Result+Lists|Gender+Results+-+Distance+FINAL'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->results($parameters);
$resultsData = $resultsParser->getContent();

var_dump($resultsData->getRecords());
