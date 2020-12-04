<?php


namespace App\Service\HAL;


use App\Dto\BrandDto;
use App\Entity\User;


class BrandHAL extends AbstractHAL
{
    /** @var BrandDto $dto */
    protected $dto;


    public function getDtoClass()
    {
        return BrandDto::class;
    }

    protected function setEmbedded()
    {
        $productHAL = new ProductHAL($this->router, $this->security, true);
        $this->dto->addEmbedded('products', $this->HalifyCollection($this->dto->getEntity()->getProducts(), $productHAL));
    }

    protected function setLinks()
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
        $this->dto->addLink('self',
            [
                'href' => $this->router->generate('brand_read', ['id' => $this->dto->getId()]),
                'method' => 'GET'
            ]);
    }

    private function updateLink()
    {
        $this->dto->addLink('update',
            [
                'href' => $this->router->generate('brand_update', ['id' => $this->dto->getId()]),
                'method' => 'PATCH'
            ]);
    }

    private function replaceLink()
    {
        $this->dto->addLink('replace',
            [
                'href' => $this->router->generate('brand_update', ['id' => $this->dto->getId()]),
                'method' => 'PUT'
            ]);
    }

    private function deleteLink()
    {
        $this->dto->addLink('delete',
            [
                'href' => $this->router->generate('brand_update', ['id' => $this->dto->getId()]),
                'method' => 'DELETE'
        ]);
    }
}