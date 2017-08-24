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
		$this->addArgument( 'directory', InputArgument::REQUIRED, 'Data sheet file' );
	}

	protected function execute( InputInterface $input, OutputInterface $output ) : int
	{
		$this->style    = new SymfonyStyle( $input, $output );
		$folder  = (string)$input->getArgument( 'directory' );

		if ( $folder[0] !== DIRECTORY_SEPARATOR )
		{
			$folder = dirname( __DIR__, 4 ) . DIRECTORY_SEPARATOR . $folder;
		}

		if ( !file_exists( $folder ) )
		{
			$this->style->error( 'Folder does not exist: ' . $folder );

			return 1;
		}

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

        $fullFilePath = $folder . DIRECTORY_SEPARATOR . 'personal.csv';

        $this->style->section( 'Start importing file: ' . $fullFilePath );

        try
        {
            $file = fopen($fullFilePath, 'rb');
            $headerRow= fgetcsv($file, 1024, ',', '"');
            $dataRow= fgetcsv($file, 1024, ',', '"');

            $personal = array_combine($headerRow, $dataRow);

            $userData = 		[
                'firstName'   => $personal['FirstName'],
                'lastName'    => ($personal['Infix'] ? "{$personal['Infix']} " : '') . $personal['LastName'],
                'topics'      => array_map(
                    function($string) {
                        return strtolower(trim($string));
                    }, explode(
                    ',',
                        $personal['tech topic interested comma separated']
                    )
                ),
                'conferences' => [],
            ];

            fclose($file);

            $fullFilePath = $folder . DIRECTORY_SEPARATOR . 'past.csv';

            $this->style->section( 'Start importing file: ' . $fullFilePath );

            $file = fopen($fullFilePath, 'rb');
            $conferences = [];
            $index = 0;

            while($row = fgetcsv($file, 1024, ',', '"')) {
                if (0 === $index++)
                    continue;

                $conferences[] = strtolower(trim($row[0]));
            }

            $userData['conferences'] = $conferences;

            /** @var User $user */
            $user = User::fromArray( $userData );

            $collectionRepository->insertDocuments( 'users', $user );
            $collectionRepository->insertDocuments( 'topics', ...$user->getTopics() );
            $collectionRepository->insertDocuments( 'conferences', ...$user->getConferences() );
            $collectionRepository->insertDocuments( 'edges', ...$user->getEdges() );

            $this->style->success( '√ Data successfully added.' );
        }
        catch ( \Throwable $e )
        {
            $this->style->error( $e->getMessage() );

            return 1;
        }

        $this->style->success( 'Data imported successfully.' );

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
