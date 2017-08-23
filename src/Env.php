<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase;

use triagens\ArangoDb\Connection;
use WeCamp\TheDevelChase\Infrastructure\Configs\ArangoConnectionConfig;

/**
 * Class Env
 * @package WeCamp\TheDevelChase
 */
final class Env
{
	/** @var array */
	private $pool = [];

	private function getSharedInstance( string $name, \Closure $createFunction )
	{
		if ( !isset( $this->pool[ $name ] ) )
		{
			$this->pool[ $name ] = $createFunction->call( $this );
		}

		return $this->pool[ $name ];
	}

	public function getArangoConnection() : Connection
	{
		return $this->getSharedInstance(
			'arangoConnection',
			function () {
				$config = new ArangoConnectionConfig();

				return new Connection( $config->toArray() );
			}
		);
	}
}
