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
					'firstname' => 'Tom',
					'lastname'  => 'Riddle',
					'topics'    => [
						'PHP', 'Docker',
					],
				],
				[
					'firstname' => 'Mary',
					'lastname'  => 'Poppins',
					'topics'    => [
						'JavaScript', 'Vagrant',
					],
				],
				[
					'firstname' => 'Jennis',
					'lastname'  => 'Joplin',
					'topics'    => [
						'PHP', 'Vagrant',
					],
				],
				[
					'firstname' => 'Elvis',
					'lastname'  => 'Presley',
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

		try
		{
			$collectionName = 'users';

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

			// Insert users
			$users = $this->testdata['users'];

			$query = "INSERT @user INTO users RETURN NEW";

			$userDocuments = [];

			foreach ( $users as $user )
			{
				$statement = new Statement(
					$this->getEnv()->getArangoConnection(),
					[
						'query'     => $query,
						'count'     => true,
						'batchSize' => 1,
						'sanatize'  => true,
						'bindVars'  => [
							'user'         => [
								'firstname' => $user['firstname'],
								'lastname'  => $user['lastname'],
							],
						],
					]
				);

				$cursor = $statement->execute();

				foreach ( $cursor as $key => $document )
				{
					$userDocuments[ $key ] = $document;
				}
			}

			print_r( $userDocuments );
		}
		catch ( \Throwable $e )
		{
			$style->error( $e->getMessage() );

			return 1;
		}

		$style->success( 'Data successfully added.' );

		return 0;
	}
}
