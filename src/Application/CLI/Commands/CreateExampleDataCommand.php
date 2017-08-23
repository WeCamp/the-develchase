<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use triagens\ArangoDb\Collection as Collection;
use triagens\ArangoDb\CollectionHandler as CollectionHandler;
use triagens\ArangoDb\DocumentHandler as DocumentHandler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use triagens\ArangoDb\Document;
use WeCamp\TheDevelChase\Env;

/**
 * Class CreateExampleDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class CreateExampleDataCommand extends AbstractCommand
{
    var $connection;

    var $testdata;

    public function __construct($name, Env $env)
    {
        parent::__construct($name, $env);

        $this->connection = $env->getArangoConnection();

        // Example data structure
        $this->testdata = [
            'user' => [
                'Tom',
                'Jerry',
                'Daffy Duck',
                'Bugs Bunny',
                'Elma Fudd'
            ],
            'topic' => [],
            'conference' => [
                'PHP North West',
                'PHP South Coast',
                'PHP Yorkshire'
            ]
        ];
    }

    protected function configure() : void
	{
		$this->setDescription( 'Fills example data to the graph database.' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$style = new SymfonyStyle( $input, $output );

		$style->title( 'Adding test data.' );

		foreach($this->testdata as $collectionName => $collectionDocuments) {
            try {
                // Create a new collection
                $style->section('Creating collection ' . $collectionName);

                $collection = new Collection($collectionName);
                $collectionHandler = new CollectionHandler($this->connection);

                // Drops an existing collection with the same name
                if ($collectionHandler->has($collection)) {
                    $collectionHandler->drop($collection);
                }

                $collectionId = $collectionHandler->create($collection);
                $documentHandler = new DocumentHandler($this->connection);

                foreach($collectionDocuments as $documentName) {
                    $style->writeln('Creating document ' . $documentName);

                    $document = new Document();
                    $document->set("name", $documentName);
                    $documentHandler->save($collectionId, $document);
                }
            } catch (\Throwable $e) {
                $style->error($e->getMessage());

                return 1;
            }
        }

		$style->success( 'Data successfully added.' );

		return 0;
	}
}
