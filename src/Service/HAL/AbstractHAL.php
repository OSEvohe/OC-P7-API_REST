<?php


namespace App\Service\HAL;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

abstract class AbstractHAL
{
    protected $dto;
    protected $dtoClass;

    /** @var bool */
    protected $noEmbed;

    /** @var bool */
    protected $noLinks;

    /** @var RouterInterface */
    protected $router;

    /** @var Security */
    protected $security;


    /**
     * BrandHAL constructor.
     * @param RouterInterface $router
     * @param Security $security
     * @param bool $noEmbed
     * @param bool $noLinks
     */
    public function __construct(RouterInterface $router, Security $security, bool $noEmbed = false, bool $noLinks = false)
    {
        $this->router = $router;
        $this->security = $security;

        $this->dtoClass = $this->getDtoClass();
        $this->noEmbed = $noEmbed;
        $this->noLinks = $noLinks;
    }


    /** @return string Name of the Dto class. */
    abstract protected function getDtoClass();

    /** Set the Dto property used for _links */
    abstract protected function setLinks();

    /** set the Dto property used for _embedded */
    abstract protected function setEmbedded();


     /** @param $entity
     */
    public function setDto($entity){
        $dtoClass= $this->dtoClass;
        $this->dto = new $dtoClass($entity);
    }

    /**
     * Set additional property (_link...) and return Dto object
     * @param $entity
     * @return mixed
     */
    public function getHAL($entity)
    {
        if (is_array($entity)) {
            return $this->getArrayEntitiesHAL($entity);
        } else {
            return $this->getEntityHAL($entity);
        }
    }

    public function getArrayEntitiesHAL(array $entities){
        $arrayHAL = [];
        foreach ($entities as $entity){
            $arrayHAL[] = $this->getEntityHAL($entity);
        }
        return $arrayHAL;
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
            $collectionHAL[] = $entityHAL->getHAL($entity);
        }

        return $collectionHAL;
    }

    /**
     * @param $entity
     * @return mixed
     */
    private function getEntityHAL($entity)
    {
        $this->setDto($entity);

        if (false === $this->noLinks) {
            $this->setLinks();
        }

        if (false === $this->noEmbed) {
            $this->setEmbedded();
        }

        return $this->dto;
    }

}