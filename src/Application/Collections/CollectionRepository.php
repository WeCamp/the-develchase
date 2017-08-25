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
	private const COLLECTION_TYPE_DOCUMENTS = 2;

	private const COLLECTION_TYPE_EDGES     = 3;

	/** @var Connection */
	private $connection;

	/** @var CollectionHandler */
	private $collectionHandler;

	public function __construct( Connection $connection )
	{
		$this->connection        = $connection;
		$this->collectionHandler = new CollectionHandler( $connection );
	}

	public function createDocumentCollection( string $collectionName ) : string
	{
		return $this->createCollection( $collectionName, self::COLLECTION_TYPE_DOCUMENTS );
	}

	public function createEdgeCollection( string $collectionName ) : string
	{
		return $this->createCollection( $collectionName, self::COLLECTION_TYPE_EDGES );
	}

	private function createCollection( string $name, int $type ) : string
	{
		$collection = new Collection( $name );
		$collection->setType( $type );

		if ( !$this->collectionHandler->has( $collection ) )
		{
			return $this->collectionHandler->create( $collection );
		}

		return $this->collectionHandler->getCollectionId( $collection->getName() );
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

	public function dropCollections( string ...$collectionNames ) : void
	{
		foreach ( $collectionNames as $collectionName )
		{
			if ( !$this->collectionHandler->has( $collectionName ) )
			{
				continue;
			}

			$this->collectionHandler->drop( $collectionName );
		}
	}
}
