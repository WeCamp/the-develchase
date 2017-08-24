<?php declare(strict_types=1);

namespace WeCamp\TheDevelChase\Application\Interfaces;

interface DocumentInterface
{
	/**
	 * @param string[] $data
	 *
	 * @return DocumentInterface
	 */
	public static function fromArray( array $data ) : DocumentInterface;

	public function toArray() : array;

	public function getKey() : string;
}
