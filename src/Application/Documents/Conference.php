<?php declare(strict_types=1);

namespace WeCamp\TheDevelChase\Application\Documents;

use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;

final class Conference implements DocumentInterface
{
	/** @var string */
	private $name;

	/**
	 * User constructor.
	 *
	 * @param string $name
	 */
	public function __construct( string $name )
	{
		$this->name = $name;
	}

	public static function fromArray( array $data ) : DocumentInterface
	{
		return new self( $data['name'] );
	}

	public function toArray() : array
	{
		return [
			'_key' => $this->getKey(),
			'name' => $this->name,
		];
	}

	public function getKey() : string
	{
		return md5( $this->name );
	}
}
