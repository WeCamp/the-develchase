<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use triagens\ArangoDb\Collection as Collection;
use triagens\ArangoDb\CollectionHandler as CollectionHandler;
use triagens\ArangoDb\Statement;
use WeCamp\TheDevelChase\Application\Documents\Conference;
use WeCamp\TheDevelChase\Application\Documents\Topic;
use WeCamp\TheDevelChase\Application\Documents\User;
use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;
use WeCamp\TheDevelChase\Env;

/**
 * Class CreateExampleDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class CreateExampleDataCommand extends AbstractCommand
{
	private $connection;

	private $testdata;

	public function __construct( $name, Env $env )
	{
		parent::__construct( $name, $env );

		$this->connection = $env->getArangoConnection();

		// Example data structure
		$this->testdata = [
			'users'       => [
				[
					'firstName' => 'Tom',
					'lastName'  => 'Riddle',
					'topics'    => [
						'PHP', 'Docker',
					],
				],
				[
					'firstName' => 'Mary',
					'lastName'  => 'Poppins',
					'topics'    => [
						'JavaScript', 'Vagrant',
					],
				],
				[
					'firstName' => 'Jennis',
					'lastName'  => 'Joplin',
					'topics'    => [
						'PHP', 'Vagrant',
					],
				],
				[
					'firstName' => 'Elvis',
					'lastName'  => 'Presley',
					'topics'    => [
						'JavaScript', 'Docker', 'PHP',
					],
				],

			],
			'topics'      => [
				[
					'name' => 'PHP',
				],
				[
					'name' => 'JavaScript',
				],
				[
					'name' => 'Docker',
				],
				[
					'name' => 'Vagrant',
				],
			],
			'conferences' => [
				[
					'name'     => 'PHP North West',
					'location' => 'Northwest, UK',
				],
				[
					'name'     => 'PHP South Coast',
					'location' => 'South Coast, USA',
				],
				[
					'name'     => 'PHP Yorkshire',
					'location' => 'Yorkshire, UK',
				],
			],
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

		$collectionHandler = new CollectionHandler( $this->connection );

		foreach($this->testdata as $collectionName => $documents) {
		    switch($collectionName) {
                case 'users':
                    $documentClassName = User::class;
                    break;
                case 'topics':
                    $documentClassName = Topic::class;
                    break;
                case 'conferences':
                    $documentClassName = Conference::class;
                    break;
                default:
                    throw new \Exception('Unknown collection name');
            }

            // Create a new collection
            $style->section( 'Creating collection ' . $collectionName );

            $collection = new Collection( $collectionName );

            // Drops an existing collection with the same name
            if ( $collectionHandler->has( $collection ) )
            {
                $collectionHandler->drop( $collection );
            }

            $collectionId = $collectionHandler->create( $collection );

            $style->writeln( 'Created collection ' . $collection . ' with ID: ' . $collectionId );

            $query = "INSERT @document INTO @@collectionName RETURN NEW";


            foreach($documents as $data) {
		        /** @var DocumentInterface $documentClassName */
		        $document = $documentClassName::fromArray($data);

                $statement = new Statement(
                    $this->getEnv()->getArangoConnection(),
                    [
                        'query'     => $query,
                        'count'     => true,
                        'batchSize' => 1,
                        'sanatize'  => true,
                        'bindVars'  => [
                            'document' => $document->toArray(),
                            '@collectionName' => $collectionName
                        ],
                    ]
                );

                $statement->execute();
            }
        }
//
//		try
//		{
//
//		}
//		catch ( \Throwable $e )
//		{
//			$style->error( $e->getMessage() );
//
//			return 1;
//		}
//
		$style->success( 'Data successfully added.' );

		return 0;
	}
}
