<?php declare(strict_types=1);

namespace WeCamp\TheDevelChase\Application\Documents;

use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;

final class User extends AbstractDocument implements DocumentInterface
{
	/** @var string */
	private $firstName;

	/** @var string */
	private $lastName;

	/** @var array */
	private $topics;

	/** @var array */
	private $conferences;

	/** @var Location */
	private $workLocation;

	/** @var Location */
	private $livingLocation;

	public function __construct(
		string $firstName,
		string $lastName,
		Location $workLocation,
		Location $livingLocation,
		array $topics,
		array $conferences
	)
	{
		$this->firstName      = $firstName;
		$this->lastName       = $lastName;
		$this->topics         = $topics;
		$this->conferences    = $conferences;
		$this->workLocation   = $workLocation;
		$this->livingLocation = $livingLocation;
	}

	public static function fromArray( array $data ) : DocumentInterface
	{
		$topics = array_map(
			function ( string $topicName )
			{
				return new Topic( $topicName );
			},
			$data['topics']
		);

		$conferences = array_map(
			function ( array $conferenceData )
			{
				return Conference::fromArray( $conferenceData );
			},
			$data['conferences']
		);

		$workLocation   = new Location( $data['workLocation'] );
		$livingLocation = new Location( $data['livingLocation'] );

		return new self( $data['firstName'], $data['lastName'], $workLocation, $livingLocation, $topics, $conferences );
	}

	/**
	 * @return array|Topic[]
	 */
	public function getTopics() : array
	{
		return $this->topics;
	}

	/**
	 * @return array|Conference[]
	 */
	public function getConferences() : array
	{
		return $this->conferences;
	}

	public function getWorkLocation() : Location
	{
		return $this->workLocation;
	}

	public function getLivingLocation() : Location
	{
		return $this->livingLocation;
	}

	public function toArray() : array
	{
		return [
			'_key'      => $this->getKey(),
			'firstName' => $this->firstName,
			'lastName'  => $this->lastName,
			'label'     => $this->firstName . ' ' . $this->lastName,
		];
	}

	public function getKey() : string
	{
		return $this->sanitizeString( $this->firstName . ' ' . $this->lastName );
	}

	public function getEdges() : array
	{
		$edges = [];
		foreach ( $this->getConferences() as $conference )
		{
			$edges[] = Edge::fromArray(
				[
					'label' => 'attending',
					'_from' => 'users/' . $this->getKey(),
					'_to'   => 'conferences/' . $conference->getKey(),
				]
			);

			foreach ( $conference->getEdges() as $edge )
			{
				$edges[] = $edge;
			}
		}

		foreach ( $this->getTopics() as $topic )
		{
			$edges[] = Edge::fromArray(
				[
					'label' => 'interested',
					'_from' => 'users/' . $this->getKey(),
					'_to'   => 'topics/' . $topic->getKey(),
				]
			);
		}

		$edges[] = Edge::fromArray(
			[
				'label' => 'worksAt',
				'_from' => 'users/' . $this->getKey(),
				'_to'   => 'locations/' . $this->getWorkLocation()->getKey(),
			]
		);

		$edges[] = Edge::fromArray(
			[
				'label' => 'livesAt',
				'_from' => 'users/' . $this->getKey(),
				'_to'   => 'locations/' . $this->getLivingLocation()->getKey(),
			]
		);

		return $edges;
	}

	public function getAllLocations() : array
	{
		$locations = [
			$this->getLivingLocation(),
			$this->getWorkLocation(),
		];

		foreach ( $this->getConferences() as $conference )
		{
			$locations[] = $conference->getLocation();
		}

		return $locations;
	}
}
