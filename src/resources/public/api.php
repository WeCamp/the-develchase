<?php declare(strict_types=1);

namespace WeCamp\TheDevelChase;

use triagens\ArangoDb\Document;
use WeCamp\TheDevelChase\Application\Collections\CollectionRepository;

require __DIR__ . '/../../../vendor/autoload.php';

$env = new Env();

$collectionRepository = new CollectionRepository( $env->getArangoConnection() );

$results = $collectionRepository->queryDocuments( $_POST['interests'] );

$json = '[';

$jsonDocuments = [];

/** @var Document $document */
foreach ( $results as $document )
{
	$jsonDocuments[] = $document->toJson();
}

$json .= implode( ",\n", $jsonDocuments );
$json .= ']';

header( 'Content-Type: application/json; charset=utf-8', true, 200 );
echo $json;
flush();
