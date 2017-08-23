<?php

require __DIR__ . '/../vendor/autoload.php';

// set up some aliases for less typing later
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Connection as ArangoConnection;
use ArangoDBClient\ConnectionOptions as ArangoConnectionOptions;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Exception as ArangoException;
use ArangoDBClient\UpdatePolicy as ArangoUpdatePolicy;

// set up some basic connection options
$connectionOptions = [
	// database name
	ArangoConnectionOptions::OPTION_DATABASE      => '_system',
	// server endpoint to connect to
	ArangoConnectionOptions::OPTION_ENDPOINT      => 'tcp://127.0.0.1:8529',
	// authorization type to use (currently supported: 'Basic')
	ArangoConnectionOptions::OPTION_AUTH_TYPE     => 'Basic',
	// user for basic authorization
	ArangoConnectionOptions::OPTION_AUTH_USER     => 'root',
	// password for basic authorization
	ArangoConnectionOptions::OPTION_AUTH_PASSWD   => 'openSesame',
	// connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
	ArangoConnectionOptions::OPTION_CONNECTION    => 'Keep-Alive',
	// connect timeout in seconds
	ArangoConnectionOptions::OPTION_TIMEOUT       => 3,
	// whether or not to reconnect when a keep-alive connection has timed out on server
	ArangoConnectionOptions::OPTION_RECONNECT     => true,
	// optionally create new collections when inserting documents
	ArangoConnectionOptions::OPTION_CREATE        => true,
	// optionally create new collections when inserting documents
	ArangoConnectionOptions::OPTION_UPDATE_POLICY => ArangoUpdatePolicy::LAST,
];

// turn on exception logging (logs to whatever PHP is configured)
ArangoException::enableLogging();

$connection = new ArangoConnection( $connectionOptions );

$collectionHandler = new ArangoCollectionHandler( $connection );
$documentHandler   = new ArangoDocumentHandler( $connection );

// clean up first
if ( $collectionHandler->has( 'users' ) )
{
	$collectionHandler->drop( 'users' );
}

// create a new collection
$userCollection = new ArangoCollection();
$userCollection->setName( 'users' );
$userCollectionId = $collectionHandler->create( $userCollection );

$user1 = new ArangoDocument();
$user1->set( 'name', 'Thijs' );
$user1->isInterestedIn = ['ReactJS', 'Redux', 'PHP basics'];

$user1Id = $documentHandler->save( 'users', $user1 );

$user2 = new ArangoDocument();
$user2->set( 'name', 'Holger' );
$user2->isInterestedIn = ['ReactJS', 'PHP basics'];
$user2Id               = $documentHandler->save( 'users', $user2 );

echo "Users collection ID: {$userCollectionId}\n";
echo "User ID Thijs: {$user1Id}\n";
echo "User ID Holger: {$user2Id}\n";

$query     = 'for u in users filter "ReactJS" IN u.isInterestedIn return u';
$statement = new \ArangoDBClient\Statement(
	$connection, [
				   'query'     => $query,
				   'count'     => true,
				   'batchSize' => 1000,
				   'bindVars'  => [
				   ],
				   'sanitize'  => true,
			   ]
);

print $statement . "\n\n";
$cursor = $statement->execute();
var_dump( $cursor->getAll() );
