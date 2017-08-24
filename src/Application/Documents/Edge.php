<?php
declare(strict_types=1);

namespace WeCamp\TheDevelChase\Application\Documents;


use WeCamp\TheDevelChase\Application\Interfaces\DocumentInterface;

final class Edge implements DocumentInterface
{
    /** @var string */
    private $label;

    /** @var string */
    private $_from;

    /** @var string */
    private $_to;

    /**
     * User constructor.
     * @param string $label
     * @param string $_from
     * @param string $_to
     */
    public function __construct(string $label, string $_from, string $_to)
    {
        $this->label = $label;
        $this->_from = $_from;
        $this->_to = $_to;
    }

    public static function fromArray(array $data): DocumentInterface
    {
        return new self($data['label'], $data['_from'], $data['_to']);
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            '_from' => $this->_from,
            '_to' => $this->_to
        ];
    }
}
