<?php

require_once __DIR__ . '/vendor/autoload.php';

$clientBuilder = new \Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientBuilder('https://HOSTNAME_SERVER.com');
$client = $clientBuilder->buildAuthenticatedByPassword(
    'API_CLIENT_ID',
    'API_CLIENT_SECRET',
    'USERNAME',
    'PASSWORD'
);

# link to the API documentation on Assets: https://api.akeneo.com/api-reference-index.html#AssetManager



# this method returns a string containing the header "Asset-media-file-code"
$uRI_LOCATION = $client->getAssetMediaFileApi()->create("images/TODD-JSON-TEST.png");

$dataAsset = [
    "code" => "1234",
    "values" => [
        "media" => [
            [
                "locale" => null,
                "channel" => null,
                "data" => $uRI_LOCATION,
            ]
        ]
    ]
];


$client->getAssetManagerApi()->upsert("leo_asset_family", "1234", $dataAsset);




