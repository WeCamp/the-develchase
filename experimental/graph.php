<?php


// set up some aliases for less typing later
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Connection as ArangoConnection;
use ArangoDBClient\ConnectionOptions as ArangoConnectionOptions;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\Exception as ArangoException;
use ArangoDBClient\Export as ArangoExport;
use ArangoDBClient\ConnectException as ArangoConnectException;
use ArangoDBClient\ClientException as ArangoClientException;
use ArangoDBClient\ServerException as ArangoServerException;
use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\UpdatePolicy as ArangoUpdatePolicy;

// get connection options from a helper file
require __DIR__ . '/../vendor/autoload.php';

$connectionOptions = [
	// database name
	ArangoConnectionOptions::OPTION_DATABASE => '_system',
	// server endpoint to connect to
	ArangoConnectionOptions::OPTION_ENDPOINT => 'tcp://127.0.0.1:8529',
	// authorization type to use (currently supported: 'Basic')
	ArangoConnectionOptions::OPTION_AUTH_TYPE => 'Basic',
	// user for basic authorization
	ArangoConnectionOptions::OPTION_AUTH_USER => 'root',
	// password for basic authorization
	ArangoConnectionOptions::OPTION_AUTH_PASSWD => 'openSesame',
	// connection persistence on server. can use either 'Close' (one-time connections) or 'Keep-Alive' (re-used connections)
	ArangoConnectionOptions::OPTION_CONNECTION => 'Keep-Alive',
	// connect timeout in seconds
	ArangoConnectionOptions::OPTION_TIMEOUT => 3,
	// whether or not to reconnect when a keep-alive connection has timed out on server
	ArangoConnectionOptions::OPTION_RECONNECT => true,
	// optionally create new collections when inserting documents
	ArangoConnectionOptions::OPTION_CREATE => true,
	// optionally create new collections when inserting documents
	ArangoConnectionOptions::OPTION_UPDATE_POLICY => ArangoUpdatePolicy::LAST,
];

try
{
	// Setup connection, graph and graph handler
	$connection   = new \ArangoDBClient\Connection($connectionOptions);
	$graphHandler = new \ArangoDBClient\GraphHandler($connection);
	$graph        = new \ArangoDBClient\Graph();
	$graph->set('_key', 'Graph');
	$graph->addEdgeDefinition(\ArangoDBClient\EdgeDefinition::createUndirectedRelation('EdgeCollection', 'VertexCollection'));
	try {
		$graphHandler->dropGraph($graph);
	} catch (\Exception $e) {
		// graph may not yet exist. ignore this error for now
	}
	$graphHandler->createGraph($graph);
	// Define some arrays to build the content of the vertices and edges
	$vertex1Array = [
		'_key'     => 'User-1',
		'name' => 'Theijs'
	];
	$vertex2Array = [
		'_key'     => 'User-2',
		'name' => 'Holger'
	];
	$edge1Array   = [
		'_key'         => 'knowledge',
		'someEdgeKey1' => 'someEdgeValue1'
	];
	// Create documents for 2 vertices and a connecting edge
	$vertex1 = \ArangoDBClient\Vertex::createFromArray($vertex1Array);
	$vertex2 = \ArangoDBClient\Vertex::createFromArray($vertex2Array);
	$edge1   = \ArangoDBClient\Edge::createFromArray($edge1Array);
	// Save the vertices
	$graphHandler->saveVertex('Graph', $vertex1);
	$graphHandler->saveVertex('Graph', $vertex2);
	// Get the vertices
	$graphHandler->getVertex('Graph', 'vertex1');
	$graphHandler->getVertex('Graph', 'vertex2');
	// check if vertex exists
	var_dump($graphHandler->hasVertex('Graph', 'vertex1'));
	// Save the connecting edge
	$graphHandler->saveEdge('Graph', $vertex1->getHandle(), $vertex2->getHandle(), 'somelabelValue', $edge1);
	// check if edge exists
	var_dump($graphHandler->hasEdge('Graph', 'edge1'));
	// Get the connecting edge
	$graphHandler->getEdge('Graph', 'edge1');
	// Remove vertices and edges
	$graphHandler->removeVertex('Graph', 'vertex1');
	$graphHandler->removeVertex('Graph', 'vertex2');
	// the connecting edge will be deleted automatically
} catch (\ArangoDBClient\ConnectException $e) {
	print $e . PHP_EOL;
} catch (\ArangoDBClient\ServerException $e) {
	print $e . PHP_EOL;
} catch (\ArangoDBClient\ClientException $e) {
	print $e . PHP_EOL;
}
