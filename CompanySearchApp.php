<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$client = new Client([
    'base_uri' => 'https://data.gov.lv/dati/lv/api/3/action/'
]);

function searchCompany
(
    Client $client,
    string $companyName
): array|string
{
    try {
        $resource_id = '25e80bf3-f107-4ab4-89ef-251b5b9374e9';

        $response = $client->
        request('GET', 'datastore_search',
            [
            'query' => [
                'q' => $companyName,
                'resource_id' => $resource_id
            ]
        ]);

        $data = json_decode(
            $response->getBody(), true
        );

        if (empty($data['result']['records'])) {
            return 'No records found for the company name.';
        }
        return $data['result']['records'];
    } catch (RequestException $e) {
        return 'Request failed: ' . $e->getMessage();
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        return 'Request failed: ' . $e->getMessage();
    }
}

$companyName = readline("Add Company name: ");
$results = searchCompany($client, $companyName);

if (is_string($results)) {
    echo $results . "\n";
    return;
}

foreach ($results as $record) {
    if (isset($record['name'])) {
        echo $record['name'] . "\n";
    }
}

// TODO: Make a table to display the results in a more readable format.
