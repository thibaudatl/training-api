<?php

require_once __DIR__ . '/vendor/autoload.php';

$clientBuilder = new \Akeneo\PimEnterprise\ApiClient\AkeneoPimEnterpriseClientBuilder('https://theakademy-c.cloud.akeneo.com');
$client = $clientBuilder->buildAuthenticatedByPassword(
	'9_3v57wsndreucoggcc8w0gogcco4cgkwsos80s0s4c8ko4gg0k0', 
	'rumu94s1gis40coc8kwwks8kg0w88wgws84k0ggk40848kscg', 
	'leo_test_6006', 
	'5993e4eab'
);

$product = json_decode(file_get_contents('/srv/pim/exercises/import products/product.json'), true);

try{
    $response = $client->getProductApi()->upsertList($product);
} catch (\Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException $e) {
    echo "Unprocessable\n";
    echo $e->getMessage();
    foreach ($e->getResponseErrors() as $error) {
        echo $error['property'] ."\n";
        echo $error['message']."\n";
    }
} catch (\Akeneo\Pim\ApiClient\Exception\UnauthorizedHttpException $e) {
    echo "Unauthorized\n";
} catch (\Akeneo\Pim\ApiClient\Exception\NotFoundHttpException $e) {
    echo "Not Found\n";
} catch (Akeneo\Pim\ApiClient\Exception\ServerErrorHttpException $e) {
    if (is_iterable($e->getMessage())) {
        foreach($e->getMessage() as $error) {
            var_dump($error);
        }
    } else {
        var_dump($e->getResponse());
    }
}



try {
    $client->getProductMediaFileApi()->create(
        "/srv/pim/exercises/akeneo.png",
        [
            "identifier" => "JIM_TEST_UPSERT",
         "attribute" => "leo_image",
         "scope" => null,
         "locale" => null
        ]
    );
} catch (\Akeneo\Pim\ApiClient\Exception\UnprocessableEntityHttpException $e) {
    echo "Unprocessable\n";
    var_dump($e->getMessage());
}

// Part on Assets


$mediaCodeComic = $client->getAssetMediaFileApi()->create("/srv/pim/exercises/akeneo.png");

$dataAsset = [
    "code" => "75YearOfComic",
    "values" => [
        "image" => [
            [
                "locale" => null,
                "channel" => null,
                "data" => $mediaCodeComic,
            ]
        ],
        "typeOfBook" => [
            [
                "locale" => null,
                "channel" => null,
                "data" => "comics"
            ]
        ],
        "nber_page" => [
            [
                "locale" => null,
                "channel" => null,
                "data" => "85"
            ]
        ]
    ]
];
$client->getAssetManagerApi()->upsert("book", "75YearOfComic", $dataAsset);