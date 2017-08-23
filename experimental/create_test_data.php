<?php

require __DIR__ . '/../vendor/autoload.php';

include('create_test_data.php');

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

// ===== Remove old data ====

if ( $collectionHandler->has( 'user' ) )
{
    $collectionHandler->drop( 'user' );
}

if ( $collectionHandler->has( 'topic' ) )
{
    $collectionHandler->drop( 'topic' );
}

if ( $collectionHandler->has( 'conference' ) )
{
    $collectionHandler->drop( 'conference' );
}

// ==== Create vertices ====

$userCollection = new ArangoCollection();
$userCollection->setName( 'user' );
$userCollectionId = $collectionHandler->create( $userCollection );

$topicCollection = new ArangoCollection();
$topicCollection->setName( 'topic' );
$topicCollectionId = $collectionHandler->create( $topicCollection );

$conferenceCollection = new ArangoCollection();
$conferenceCollection->setName( 'conference' );
$conferenceCollectionId = $collectionHandler->create( $conferenceCollection );

// ==== Create edges ====

$user1 = new ArangoDocument();
$user1->set( 'name', 'Thijs' );
$user1->isInterestedIn = ['ReactJS', 'Redux', 'PHP basics'];

$user1Id = $documentHandler->save( 'users', $user1 );

//edge definitions
//interested in - user to topic
//presents - conference to topic
//attended - user to conference



