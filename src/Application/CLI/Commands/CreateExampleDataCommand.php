<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use WeCamp\TheDevelChase\Application\Collections\CollectionRepository;
use WeCamp\TheDevelChase\Application\Documents\User;

/**
 * Class CreateExampleDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class CreateExampleDataCommand extends AbstractCommand
{
	/** @var SymfonyStyle */
	private $style;

	protected function configure() : void
	{
		$this->setDescription( 'Fills example data to the graph database.' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$this->style = new SymfonyStyle( $input, $output );
		$this->style->title( 'Adding test data.' );

		$testData = require __DIR__ . '/../../../resources/ExampleData.php';

		$collectionRepository = new CollectionRepository( $this->getEnv()->getArangoConnection() );
		$collections = [
		    [
                'name' => 'users',
                'options' => ['type' => 2]
            ],
            [
                'name' => 'topics',
                'options' => ['type' => 2]
            ],
            [
                'name' => 'conferences',
                'options' => ['type' => 2]
            ],
            [
                'name' => 'edges',
                'options' => ['type' => 3]
            ],
        ];
		$this->createCollections( $collectionRepository, $collections );

		foreach ( (array)$testData['users'] as $userData )
		{
			/** @var User $user */
			$user = User::fromArray( $userData );

			$collectionRepository->insertDocuments( 'users', $user );
			$collectionRepository->insertDocuments( 'topics', ...$user->getTopics() );
			$collectionRepository->insertDocuments( 'conferences', ...$user->getConferences() );
			$collectionRepository->insertDocuments( 'edges', ...$user->getEdges() );
        }

		$this->style->success( '√ Data successfully added.' );

		return 0;
	}

	private function createCollections( CollectionRepository $collectionRepository, array $collections ) : void
	{
		foreach ( $collections as $collection )
		{
			$this->style->section( 'Creating collection ' . $collection['name'] );

			$collectionId = $collectionRepository->create( $collection['name'], $collection['options'] );

			$this->style->writeln( '<fg=green>√ CollectionRepository-ID: ' . $collectionId . '</>' );
		}
	}
}
