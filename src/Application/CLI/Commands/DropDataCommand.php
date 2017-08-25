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

/**
 * Class DropDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class DropDataCommand extends AbstractCommand
{
	private $style;

	protected function configure()
	{
		$this->setDescription( 'Drops collections from the graph database.' );
		$this->addArgument(
			'collectionNames',
			InputArgument::REQUIRED | InputArgument::IS_ARRAY,
			'List of collections to drop.'
		);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$this->style = new SymfonyStyle( $input, $output );

		$collectionNames      = (array)$input->getArgument( 'collectionNames' );
		$collectionRepository = new CollectionRepository( $this->getEnv()->getArangoConnection() );

		$this->style->section( 'Dropping collections:' );
		$this->style->listing( $collectionNames );

		try
		{
			$collectionRepository->dropCollections( ...$collectionNames );

			$this->style->success( '√ Drop successful.' );

			return 0;
		}
		catch ( \Throwable $e )
		{
			$this->style->error( get_class( $e ) . ': ' . $e->getMessage() );

			return 1;
		}
	}

}
