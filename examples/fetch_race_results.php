<?php
declare(strict_types=1);

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '185603',
    'key' => 'a8adcdabf3d270ca1cb5f11c30b914d4',
    'contest' => '2',
    'listname' => 'Result+Lists%7CAge+Group+Results'
];

$client = new \Sportic\Omniresult\RaceResults\RaceResultsClient();
$resultsParser = $client->results($parameters);
$resultsData = $resultsParser->getContent();

var_dump($resultsData);
