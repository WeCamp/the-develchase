<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WeCamp\TheDevelChase\Application\Collections\CollectionRepository;
use WeCamp\TheDevelChase\Application\Documents\User;

/**
 * Class ImportDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class ImportDataCommand extends AbstractCommand
{
	/** @var SymfonyStyle */
	private $style;

	protected function configure() : void
	{
		$this->setDescription( 'Imports data into the graph database.' );
		$this->addArgument(
			'directory',
			InputArgument::OPTIONAL,
			'Data sheet file',
			__DIR__ . '/../../../../data'
		);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$this->style   = new SymfonyStyle( $input, $output );
		$baseDirectory = (string)$input->getArgument( 'directory' );

		if ( $baseDirectory[0] !== DIRECTORY_SEPARATOR )
		{
			$baseDirectory = dirname( __DIR__, 4 ) . DIRECTORY_SEPARATOR . $baseDirectory;
		}

		if ( !file_exists( $baseDirectory ) )
		{
			$this->style->error( 'Folder does not exist: ' . $baseDirectory );

			return 1;
		}

		$collectionRepository = new CollectionRepository( $this->getEnv()->getArangoConnection() );

		$this->createDocumentCollections( $collectionRepository, 'users', 'topics', 'conferences', 'locations' );
		$this->createEdgeCollections( $collectionRepository, 'edges' );

		$directory = new \DirectoryIterator( $baseDirectory );

		foreach ( $directory as $item )
		{
			if ( $item->isDot() || !$item->isDir() )
			{
				continue;
			}

			$this->importDataFromFolder( $collectionRepository, $item->getRealPath() );
		}

		$this->style->success( 'Data imported successfully.' );

		return 0;
	}

	private function createDocumentCollections(
		CollectionRepository $collectionRepository,
		string ...$collectionNames
	) : void
	{
		foreach ( $collectionNames as $collectionName )
		{
			$this->style->section( 'Creating document collection ' . $collectionName );

			$collectionId = $collectionRepository->createDocumentCollection( $collectionName );

			$this->style->writeln( '<fg=green>√ Collection-ID: ' . $collectionId . '</>' );
		}
	}

	private function createEdgeCollections(
		CollectionRepository $collectionRepository,
		string ...$collectionNames
	) : void
	{
		foreach ( $collectionNames as $collectionName )
		{
			$this->style->section( 'Creating edge collection ' . $collectionName );

			$collectionId = $collectionRepository->createEdgeCollection( $collectionName );

			$this->style->writeln( '<fg=green>√ Collection-ID: ' . $collectionId . '</>' );
		}
	}

	private function commaSeparatedListToArray( string $listString ) : array
	{
		$list = array_map(
			function ( $string )
			{
				return strtolower( trim( $string ) );
			},
			explode(
				',',
				$listString
			)
		);

		return array_filter( $list );
	}

	private function importDataFromFolder( CollectionRepository $collectionRepository, string $folder ) : void
	{
		$this->style->section( 'Start importing from folder: ' . $folder );

		try
		{
			$fullFilePath = $folder . DIRECTORY_SEPARATOR . 'personal.csv';
			$file         = fopen( $fullFilePath, 'rb' );
			$headerRow    = fgetcsv( $file, 1024 );
			$dataRow      = fgetcsv( $file, 1024 );

			$personal = array_combine( $headerRow, $dataRow );

			$userData = [
				'firstName'      => $personal['FirstName'],
				'lastName'       => ($personal['Infix'] ? "{$personal['Infix']} " : '') . $personal['LastName'],
				'livingLocation' => $personal['home town'],
				'workLocation'   => $personal['work location'],
				'topics'         => $this->commaSeparatedListToArray(
					$personal['tech topic interested comma separated']
				),
				'conferences'    => [],
			];

			fclose( $file );

			$fullFilePath = $folder . DIRECTORY_SEPARATOR . 'past.csv';

			$this->style->section( 'Start importing file: ' . $fullFilePath );

			$file        = fopen( $fullFilePath, 'rb' );
			$conferences = [];
			$index       = 0;

			while ( $row = fgetcsv( $file, 1024 ) )
			{
				if ( 0 === $index++ )
				{
					continue;
				}

				$conferences[] = [
					'name'     => strtolower( trim( $row[0] ) ),
					'location' => strtolower( trim( $row[1] ) ),
					'topics'   => $this->commaSeparatedListToArray( $row[2] ),
				];
			}

			$userData['conferences'] = $conferences;

			/** @var User $user */
			$user = User::fromArray( $userData );

			$collectionRepository->insertDocuments( 'users', $user );
			$collectionRepository->insertDocuments( 'topics', ...$user->getTopics() );
			$collectionRepository->insertDocuments( 'conferences', ...$user->getConferences() );
			$collectionRepository->insertDocuments( 'locations', ...$user->getAllLocations() );
			$collectionRepository->insertDocuments( 'edges', ...$user->getEdges() );

			$this->style->success( '√ Data successfully added from folder ' . $folder );
		}
		catch ( \Throwable $e )
		{
			$this->style->error( $e->getMessage() );
		}
	}
}
