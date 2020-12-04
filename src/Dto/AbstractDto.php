<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Ignore;

abstract class AbstractDto
{
    protected $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     * @Ignore
     */
    public function getEntity()
    {
        return $this->entity;
    }
}