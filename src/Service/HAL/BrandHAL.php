<?php


namespace App\Service\HAL;


use App\Dto\BrandDto;
use App\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;


class BrandHAL extends AbstractHAL
{
    /** @var BrandDto $dto */
    protected $dto;

    /** @var RouterInterface */
    protected $router;

    /** @var Security */
    private $security;


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

        parent::__construct($noEmbed, $noLinks);
    }


    public function getDtoClass()
    {
        return BrandDto::class;
    }


    public function getHAL()
    {
        $this->setLinks();
        $this->setEmbedded();

        return parent::getHAL();
    }


    private function setEmbedded()
    {
        $productHAL = new ProductHAL($this->router, $this->security, true);
        $this->dto->addEmbedded([
            'products' => $this->HalifyCollection($this->dto->getEntity()->getProducts(), $productHAL)
        ]);
    }

    private function setLinks()
    {
        $this->selfLink();
        if ($this->security->isGranted(User::USER_ADMIN)) {
            $this->updateLink();
            $this->replaceLink();
            $this->deleteLink();
        }
    }

    private function selfLink()
    {
        $this->dto->addLink(["self" =>
            [
                "href" => $this->router->generate("brand_read", ['id' => $this->dto->getId()]),
                "method" => "GET"
            ]
        ]);
    }

    private function updateLink()
    {
        $this->dto->addLink(["update" =>
            [
                "href" => $this->router->generate("brand_update", ['id' => $this->dto->getId()]),
                "method" => "PATCH"
            ]
        ]);
    }

    private function replaceLink()
    {
        $this->dto->addLink(["replace" =>
            [
                "href" => $this->router->generate("brand_update", ['id' => $this->dto->getId()]),
                "method" => "PUT"
            ]
        ]);
    }

    private function deleteLink()
    {
        $this->dto->addLink(["delete" =>
            [
                "href" => $this->router->generate("brand_update", ['id' => $this->dto->getId()]),
                "method" => "DELETE"
            ]
        ]);
    }
}