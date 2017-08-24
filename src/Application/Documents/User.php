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

	/**
	 * User constructor.
	 *
	 * @param string             $firstName
	 * @param string             $lastName
	 * @param array|Topic[]      $topics
	 * @param array|Conference[] $conferences
	 */
	public function __construct( string $firstName, string $lastName, array $topics, array $conferences )
	{
		$this->firstName   = $firstName;
		$this->lastName    = $lastName;
		$this->topics      = $topics;
		$this->conferences = $conferences;
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
			function ( string $conferenceName )
			{
				return new Conference( $conferenceName );
			},
			$data['conferences']
		);

		return new self( $data['firstName'], $data['lastName'], $topics, $conferences );
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

	public function toArray() : array
	{
		return [
			'_key'      => $this->getKey(),
			'firstName' => $this->firstName,
			'lastName'  => $this->lastName,
            'label' => $this->firstName . ' ' . $this->lastName
		];
	}

	public function getKey() : string
	{
		return  $this->sanitizeString($this->firstName . ' ' . $this->lastName);
	}

	public function getEdges(): array
    {
        $edges = [];
        foreach($this->getConferences() as $conference) {
            $edges[] = Edge::fromArray(
                [
                    'label' => 'attending',
                    '_from' => 'users/' . $this->getKey(),
                    '_to' => 'conferences/' . $conference->getKey()
                ]
            );
        }

        foreach($this->getTopics() as $topic) {
            $edges[] = Edge::fromArray(
                [
                    'label' => 'interested',
                    '_from' => 'users/' . $this->getKey(),
                    '_to' => 'topics/' . $topic->getKey()
                ]
            );
        }

        return $edges;
    }
}
