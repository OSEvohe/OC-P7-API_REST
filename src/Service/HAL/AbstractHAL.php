<?php


namespace App\Service\HAL;


use App\Entity\Company;
use App\Util\UtilHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

abstract class AbstractHAL
{
    use IndexEmbeddableTrait;

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

    /** @var Request */
    protected $request;

    /** @var AbstractHAL */
    protected $entityListHAL;
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /** @return string Get the DTO class used by this object */
    abstract protected function getDtoClass(): string;

    /** Set _embedded field, can use setEmbeddedData methods with appropriate parameters */
    abstract protected function setEmbedded(): void;

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
        $this->noEmbed = $noEmbed;
        $this->noLinks = $noLinks;
        $this->requestStack = $requestStack;

        $this->dtoClass = $this->getDtoClass();
    }


    /**
     * @param $entity
     */
    public function setDto($entity)
    {
        $dtoClass = $this->dtoClass;
        $this->dto = new $dtoClass($entity);
    }


    /**
     * Set additional property (_link...) and return Dto object
     * @param $entity
     * @return mixed
     */
    public function getHAL($entity)
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


    /**
     * Halify every objects in a collection of entity
     * @param $collection
     * @param AbstractHAL $entityHAL HALifier of the entity inside the collection
     * @return array
     */
    protected function HalifyCollection($collection, AbstractHAL $entityHAL): array
    {
        $collectionHAL = [];
        foreach ($collection as $entity) {
            $collectionHAL[] = $entityHAL->getHAL($entity);
        }

        return $collectionHAL;
    }


    public function getNewHAL($className, $noEmbed = false, $noLinks = false)
    {
        return new $className($this->router, $this->security, $this->requestStack, $noEmbed, $noLinks);
    }


    protected function setLinks()
    {
        $this->addLink('self', 'read', 'GET');
        if ($this->security->isGranted(Company::USER_ADMIN)) {
            $this->addLink('update', 'update', 'PATCH');
            $this->addLink('replace', 'update', 'PUT');
            $this->addLink('delete', 'delete', 'DELETE');
        }
    }

    protected function addLink($rel, $action, $method)
    {
        $entityName = strtolower((new UtilHelper())->getShortClassName($this->dto->getEntity()));
        $this->dto->addLink($rel,
            [
                'href' => $this->router->generate($entityName . '_' . $action, ['id' => $this->dto->getId()]),
                'method' => $method
            ]);
    }

    protected function setEmbeddedData($data, string $entityHALClass, string $embeddedFieldName = 'results')
    {
        $entityHAL = $this->getNewHAL($entityHALClass, true);
        if (is_iterable($data)) {
            $this->dto->addEmbedded($embeddedFieldName, $this->HalifyCollection($data, $entityHAL));
        } else {
            $this->dto->addEmbedded($embeddedFieldName, $entityHAL->getHAL($data));
        }
    }
}