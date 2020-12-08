<?php


namespace App\Dto;


use stdClass;

trait LinksTrait
{
    private $_links = [];

    /**
     * @param string $key
     * @param $link
     */
    public function addLink(string $key, $link): void
    {
        $this->_links[$key] = $link;
    }
}