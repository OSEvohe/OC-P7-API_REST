<?php


namespace App\Dto;


trait LinksTrait
{
    private $_links = [];

    /**
     * @param array $link
     */
    public function addLink(array $link): void
    {
        $this->_links[] = $link;
    }
}