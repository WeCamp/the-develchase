<?php

namespace WeCamp\TheDevelChase\Application\Interfaces;

interface DocumentInterface
{
    /**
     * @param string[] $data
     * @return DocumentInterface
     */
    public static function fromArray(array $data): DocumentInterface;
    public function toArray(): array;
}
