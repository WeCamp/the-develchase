<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class ImportDataCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class ImportDataCommand extends AbstractCommand
{
	protected function configure() : void
	{
		$this->setDescription( 'Imports data into the graph database.' );
		$this->addArgument( 'file', InputArgument::REQUIRED, 'Data sheet file' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$style    = new SymfonyStyle( $input, $output );
		$filePath = (string)$input->getArgument( 'file' );

		if ( $filePath[0] !== DIRECTORY_SEPARATOR )
		{
			$filePath = dirname( __DIR__, 4 ) . DIRECTORY_SEPARATOR . $filePath;
		}

		if ( !file_exists( $filePath ) )
		{
			$style->error( 'File does not exist: ' . $filePath );

			return 1;
		}

		$style->section( 'Start importing file: ' . $filePath );

		try
		{
			// Import goes here
		}
		catch ( \Throwable $e )
		{
			$style->error( $e->getMessage() );

			return 1;
		}

		$style->success( 'Data imported successfully.' );

		return 0;
	}
}
