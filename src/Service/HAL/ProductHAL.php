<?php


namespace App\Service\HAL;


use App\Dto\ProductDto;
use App\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;


class ProductHAL extends AbstractHAL
{
    /** @var ProductDto */
    protected $dto;

    /** @var RouterInterface */
    protected $router;

    /** @var Security */
    private $security;


    public function __construct(RouterInterface $router, Security $security, bool $noEmbed = false, bool $noLinks = false)
    {
        $this->router = $router;
        $this->security = $security;

        parent::__construct($noEmbed, $noLinks);
    }


    protected function getDtoClass()
    {
        return ProductDto::class;
    }


    public function getHAL()
    {
        if (false === $this->noLinks) {
            $this->setLinks();
        }

        if (false === $this->noEmbed) {
            $this->setEmbedded();
        }

        return parent::getHAL();
    }


    private function setEmbedded()
    {
        $brandHAL = new BrandHAL($this->router, $this->security, true);
        $this->dto->addEmbedded([
            'brand' => $this->HalifyEntity($this->dto->getEntity()->getBrand(), $brandHAL)
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
                "href" => $this->router->generate("product_read", ['id' => $this->dto->getId()]),
                "method" => "GET"
            ]
        ]);
    }

    private function updateLink()
    {
        $this->dto->addLink(["update" =>
            [
                "href" => $this->router->generate("product_update", ['id' => $this->dto->getId()]),
                "method" => "PATCH"
            ]
        ]);
    }

    private function replaceLink()
    {
        $this->dto->addLink(["replace" =>
            [
                "href" => $this->router->generate("product_update", ['id' => $this->dto->getId()]),
                "method" => "PUT"
            ]
        ]);
    }

    private function deleteLink()
    {
        $this->dto->addLink(["delete" =>
            [
                "href" => $this->router->generate("product_update", ['id' => $this->dto->getId()]),
                "method" => "DELETE"
            ]
        ]);
    }
}