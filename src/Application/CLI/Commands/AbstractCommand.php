<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Application\CLI\Commands;

use Symfony\Component\Console\Command\Command;
use WeCamp\TheDevelChase\Env;

/**
 * Class AbstractCommand
 * @package WeCamp\TheDevelChase\Application\CLI\Commands
 */
abstract class AbstractCommand extends Command
{
	/** @var Env */
	private $env;

	public function __construct( string $name, Env $env )
	{
		parent::__construct( $name );
		$this->env = $env;
	}

	final protected function getEnv() : Env
	{
		return $this->env;
	}
}
