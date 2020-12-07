<?php


namespace App\Service\HAL;


use App\Dto\IndexDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

abstract class AbstractHAL
{
    protected $dto;
    protected $dtoClass;
    protected $dtoIndex;

    /** @var bool */
    protected $noEmbed;

    /** @var bool */
    protected $noLinks;

    /** @var RouterInterface */
    protected $router;

    /** @var Security */
    protected $security;

    /** @var Request */
    private $request;

    /** @var array */
    protected $entityList;

    /** @var AbstractHAL */
    protected $entityListHAL;
    /**
     * @var RequestStack
     */
    protected $requestStack;


    /**
     * BrandHAL constructor.
     * @param RouterInterface $router
     * @param Security $security
     * @param RequestStack $requestStack
     * @param bool $noEmbed
     * @param bool $noLinks
     */
    public function __construct(RouterInterface $router, Security $security, RequestStack $requestStack, bool $noEmbed = false, bool $noLinks = false)
    {
        $this->router = $router;
        $this->security = $security;
        $this->request = $requestStack->getCurrentRequest();

        $this->dtoClass = $this->getDtoClass();
        $this->noEmbed = $noEmbed;
        $this->noLinks = $noLinks;
        $this->requestStack = $requestStack;
    }


    /** @return string Name of the Dto class. */
    abstract protected function getDtoClass();

    /** Set the Dto property used for _links */
    abstract protected function setLinks();

    /** set the Dto property used for _embedded */
    abstract protected function setEmbedded();

    abstract protected function setIndexLinks();

    abstract protected function setIndexEmbedded();

    abstract protected function setIndexPagination();


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
            return $this->getEntityHAL($entity);
    }

    /**
     * Halify every objects in a collection of entity
     * @param $collection
     * @param AbstractHAL $entityHAL HALifier of the entity inside the collection
     * @return array
     */
    protected function HalifyCollection ($collection, AbstractHAL $entityHAL): array
    {
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
    protected function getEntityHAL($entity)
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

    public function getEntityListHAL($entityList): IndexDto
    {
        $this->dtoIndex = new IndexDto(null);
        $this->entityList = $entityList;

        $this->setIndexPagination();
        $this->setIndexLinks();
        $this->setIndexEmbedded();

        return $this->dtoIndex;
    }

    public function getNewHAL($className, $noEmbed = false, $noLinks = false){
        return new $className($this->router, $this->security, $this->requestStack, $noEmbed, $noLinks);
    }

}