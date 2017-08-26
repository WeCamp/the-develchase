<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use triagens\ArangoDb\Document;
use WeCamp\TheDevelChase\Application\Collections\CollectionRepository;

/**
 * Class FindInterestingConferencesCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
final class FindInterestingConferencesCommand extends AbstractCommand
{
	/** @var SymfonyStyle */
	private $style;

	protected function configure() : void
	{
		$this->setDescription( 'Finds conferences matching your interests.' );
		$this->addArgument(
			'interests',
			InputArgument::REQUIRED | InputArgument::IS_ARRAY,
			"List of topics you're interested in"
		);
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$collectionRepository = new CollectionRepository( $this->getEnv()->getArangoConnection() );

		$this->style = new SymfonyStyle( $input, $output );
		$interests   = (array)$input->getArgument( 'interests' );

		$this->style->writeln( 'Searching conferences for interests:' );
		$this->style->listing( $interests );

		$results = $collectionRepository->queryDocuments( $interests );

		$tableRows = [];

		/** @var Document $document */
		foreach ( $results as $document )
		{
			$tableRows[] = json_decode( $document->toJson(), true );
		}

		$this->style->table( ['key' => 'ID', 'conference' => 'Conf Name', 'topics' => 'Topics'], $tableRows );

		return 0;
	}
}
