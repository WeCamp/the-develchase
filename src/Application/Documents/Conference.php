<?php declare(strict_types=1);

namespace WeCamp\TheDevelChase\Application\Documents;

use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;

final class Conference extends AbstractDocument implements DocumentInterface
{
	/** @var string */
	private $name;

	/** @var array|Topic[] */
	private $topics;

	/** @var Location */
	private $location;

	public function __construct( string $name, Location $location, array $topics )
	{
		$this->name     = $name;
		$this->location = $location;
		$this->topics   = $topics;
	}

	public static function fromArray( array $data ) : DocumentInterface
	{
		$topics = array_map(
			function ( string $topic )
			{
				return new Topic( $topic );
			},
			$data['topics']
		);

		$location = new Location( $data['location'] );

		return new self( $data['name'], $location, $topics );
	}

	public function toArray() : array
	{
		return [
			'_key'  => $this->getKey(),
			'name'  => $this->name,
			'label' => $this->name,
		];
	}

	public function getKey() : string
	{
		return $this->sanitizeString( $this->name );
	}

	/**
	 * @return array|Topic[]
	 */
	public function getTopics() : array
	{
		return $this->topics;
	}

	public function getLocation() : Location
	{
		return $this->location;
	}

	public function getEdges() : array
	{
		$edges = [];
		foreach ( $this->getTopics() as $topic )
		{
			$edges[] = Edge::fromArray(
				[
					'label' => 'presents',
					'_from' => 'conferences/' . $this->getKey(),
					'_to'   => 'topics/' . $topic->getKey(),
				]
			);
		}

		$edges[] = Edge::fromArray(
			[
				'label' => 'locatedAt',
				'_from' => 'conferences/' . $this->getKey(),
				'_to'   => 'locations/' . $this->getLocation()->getKey(),
			]
		);

		return $edges;
	}
}
