<?php


namespace App\Service\HAL;


use Doctrine\Common\Collections\Collection;
use PhpParser\Node\Expr\Cast\Bool_;

abstract class AbstractHAL
{
    protected $dto;
    protected $dtoClass;

    /** @var bool */
    protected $noEmbed;

    /** @var bool */
    protected $noLinks;


    public function __construct(bool $noEmbed = false, bool $noLinks = false)
    {
        $this->dtoClass = $this->getDtoClass();
        $this->noEmbed = $noEmbed;
        $this->noLinks = $noLinks;
    }

    /**
     * @return string Name of the Dto class.
     */
    abstract protected function getDtoClass();
    /*
     * ie : return SomeEntityDto::class
     */


     /** @param $entity
     */
    public function setDto($entity){
        $dtoClass= $this->dtoClass;
        $this->dto = new $dtoClass($entity);
    }

    /**
     * Set additional property (_link...) and return Dto object
     */
    public function getHAL(){
        return $this->dto;
    }

    /**
     * Halify every objects in a collection of entity
     * @param Collection $collection a collection of entity
     * @param AbstractHAL $entityHAL HALifier of the entity inside the collection
     * @return array
     */
    protected function HalifyCollection (Collection $collection, AbstractHAL $entityHAL){
        $collectionHAL = [];
        foreach ($collection as $entity){
            $entityHAL->setDto($entity);
            $collectionHAL[] = $entityHAL->getHAL();
        }

        return $collectionHAL;
    }

    protected function HalifyEntity ($entity, AbstractHAL $entityHAL){
        $entityHAL->setDto($entity);
        return $entityHAL->getHAL();
    }
}