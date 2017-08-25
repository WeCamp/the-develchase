<?php declare(strict_types=1);

namespace WeCamp\TheDevelChase;

use WeCamp\TheDevelChase\Application\Collections\CollectionRepository;

$env = new Env();

$collectionRepository = new CollectionRepository($env->getArangoConnection());

$results = $collectionRepository->queryDocuments($_POST['interests']);

$json = '[';

/** @var Document $document */
foreach ( $results as $document )
{
    $json .= $document->toJson();
}

$json .= ']';
