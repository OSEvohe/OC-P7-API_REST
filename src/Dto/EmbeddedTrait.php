<?php


namespace App\Dto;


trait EmbeddedTrait
{
    private $_embedded = [];

    /**
     * @param array $embed
     */
    public function addEmbedded(array $embed): void
    {
        $this->_embedded[] = $embed;
    }
}