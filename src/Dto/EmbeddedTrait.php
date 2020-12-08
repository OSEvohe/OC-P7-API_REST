<?php


namespace App\Dto;


trait EmbeddedTrait
{
    private $_embedded = [];

    /**
     * @param string $key
     * @param $embed
     */
    public function addEmbedded(string $key, $embed): void
    {
        $this->_embedded[$key] = $embed;
    }
}