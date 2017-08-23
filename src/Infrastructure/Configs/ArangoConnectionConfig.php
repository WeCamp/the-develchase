<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace WeCamp\TheDevelChase\Infrastructure\Configs;

use triagens\ArangoDb\ConnectionOptions;

/**
 * Class ArangoConnectionConfig
 * @package WeCamp\TheDevelChase\Infrastructure\Configs
 */
final class ArangoConnectionConfig
{
	/** @var array */
	private $configData;

	public function __construct()
	{
		$this->configData = require __DIR__ . '/../../../config/ArangoConnection.php';
	}

	public function toArray() : array
	{
		return $this->configData;
	}

	public function getDatabase() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_DATABASE ];
	}

	public function getEndpoint() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_ENDPOINT ];
	}

	public function getAuthType() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_AUTH_TYPE ];
	}

	public function getAuthUser() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_AUTH_USER ];
	}

	public function getAuthPassword() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_AUTH_PASSWD ];
	}

	public function getConnectionType() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_CONNECTION ];
	}

	public function getTimeout() : int
	{
		return (int)$this->configData[ ConnectionOptions::OPTION_TIMEOUT ];
	}

	public function tryReconnect() : bool
	{
		return (bool)$this->configData[ ConnectionOptions::OPTION_RECONNECT ];
	}

	public function tryCreate() : bool
	{
		return (bool)$this->configData[ ConnectionOptions::OPTION_CREATE ];
	}

	public function getUpdatePolicy() : string
	{
		return (string)$this->configData[ ConnectionOptions::OPTION_UPDATE_POLICY ];
	}
}
