<?php
declare(strict_types=1);

namespace WeCamp\TheDevelChase\Application\Documents;


use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;

final class User implements DocumentInterface
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
     * @param string $firstName
     * @param string $lastName
     */
    public function __construct(string $firstName, string $lastName, array $topics, array $conferences)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->topics = $topics;
        $this->conferences = $conferences;
    }

    public static function fromArray(array $data): DocumentInterface
    {
        return new self($data['firstName'], $data['lastName']);
    }

    public function toArray(): array
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName
        ];
    }
}
