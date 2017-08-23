<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CreateExampleDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class CreateExampleDataCommand extends AbstractCommand
{
	protected function configure() : void
	{
		$this->setDescription( 'Fills example data to the graph database.' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$style = new SymfonyStyle( $input, $output );
		$style->section( 'Adding example data...' );

		try
		{
			$style->writeln( 'Adding users...' );
			$style->writeln( 'Adding topics...' );
			$style->writeln( 'Adding conferences...' );
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
