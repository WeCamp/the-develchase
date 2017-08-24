<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\Collections;

use triagens\ArangoDb\Collection;
use triagens\ArangoDb\CollectionHandler;
use triagens\ArangoDb\Connection;
use triagens\ArangoDb\Statement;
use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;

/**
 * Class CollectionRepository
 * @package WeCamp\TheDevelChase\Application\Collections
 */
final class CollectionRepository
{
	/** @var Connection */
	private $connection;

	/** @var CollectionHandler */
	private $collectionHandler;

	public function __construct( Connection $connection )
	{
		$this->connection        = $connection;
		$this->collectionHandler = new CollectionHandler( $connection );
	}

	public function create( string $name ) : string
	{
		$collection = new Collection( $name );

		// Drops an existing collection with the same name
		if ( $this->collectionHandler->has( $collection ) )
		{
			$this->collectionHandler->drop( $collection );
		}

		return $this->collectionHandler->create( $collection );
	}

	public function insertDocuments( string $collectionName, DocumentInterface ...$documents ) : void
	{
		$query = 'UPSERT { _key: @key } INSERT @document UPDATE @document IN @@collectionName';

		foreach ( $documents as $document )
		{
			$statement = new Statement(
				$this->connection,
				[
					'query'     => $query,
					'count'     => true,
					'batchSize' => 1,
					'sanatize'  => true,
					'bindVars'  => [
						'key'             => $document->getKey(),
						'document'        => $document->toArray(),
						'@collectionName' => $collectionName,
					],
				]
			);

			$statement->execute();
		}
	}
}