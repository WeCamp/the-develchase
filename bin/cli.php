<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Bin;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;
use WeCamp\TheDevelChase\Application\CLI\Commands\CreateExampleDataCommand;
use WeCamp\TheDevelChase\Application\CLI\Commands\ImportDataCommand;
use WeCamp\TheDevelChase\Env;

require __DIR__ . '/../vendor/autoload.php';

$env = new Env();
$app = new Application( 'TheDevelChase', '0.1.0' );

try
{
	$app->add( new CreateExampleDataCommand( 'create:example-data', $env ) );
	$app->add( new ImportDataCommand( 'import:data', $env ) );

	$exitCode = $app->run();

	exit( $exitCode );
}
catch ( RuntimeException $e )
{
	echo 'Uncaught ' . get_class( $e ) . ' with message: ' . $e->getMessage();
	exit( 1 );
}
