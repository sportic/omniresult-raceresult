<?php
declare(strict_types=1);

use Sportic\Omniresult\Common\Content\ListContent;
use Sportic\Omniresult\RaceResults\RaceResultsClient;

require '../vendor/autoload.php';

$parameters = [
    'eventId' => '325564',
    'key' => '37ee7f73f83a033e444029cbc1e3951b',
    'contest' => '4',
    'listname' => 'Lists|Finisher List 10K YoPro'
];

$client = new RaceResultsClient();
$resultsParser = $client->results($parameters);

/** @var ListContent $resultsData */
$resultsData = $resultsParser->getContent();

$results = $resultsData->getRecords();
?>
<style>
    /*Made with https://www.htmltables.io/*/
    .table_component {
        overflow: auto;
        width: 100%;
    }

    .table_component table {
        border: 1px solid #dededf;
        height: 100%;
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        border-spacing: 1px;
        text-align: left;
    }

    .table_component caption {
        caption-side: top;
        text-align: left;
    }

    .table_component th {
        border: 1px solid #dededf;
        background-color: #eceff1;
        color: #000000;
        padding: 5px;
    }

    .table_component td {
        border: 1px solid #dededf;
        background-color: #ffffff;
        color: #000000;
        padding: 5px;
    }
</style>
<div class="table_component ">

<table>
    <thead>
    <tr>
        <th>BIB</th>
        <th>PosGen</th>
        <th>PosGender</th>
        <th>PosCat</th>
        <th>Name</th>
        <th>Category</th>
        <th>Time</th>
        <th>Laps</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $record) { ?>
            <?php
            /** @var \Sportic\Omniresult\Common\Models\Result $record */
            ?>
            <tr>
                <td><?= $record->getBib(); ?></td>
                <td><?= $record->getPosGen(); ?></td>
                <td><?= $record->getPosGender(); ?></td>
                <td><?= $record->getPosCategory(); ?></td>
                <td><?= $record->getFullName(); ?></td>
                <td><?= $record->getCategory(); ?></td>
                <td><?= $record->getTime(); ?></td>
                <td>
                    <?php
                    $laps = $record->getSplits();
                    $lapsString = '';
                    foreach ($laps as $lap) {
                        $lapsString .= $lap->getLap() . ', ';
                    }
                    ?>
                    <?= rtrim($lapsString, ', '); ?>
                </td>
            </tr>
    <?php } ?>
    </tbody>
</table>
</div>
