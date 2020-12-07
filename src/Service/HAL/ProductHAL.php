<?php


namespace App\Service\HAL;


use App\Dto\ProductDto;
use App\Entity\User;


class ProductHAL extends AbstractHAL
{
    /** @var ProductDto */
    protected $dto;


    protected function getDtoClass()
    {
        return ProductDto::class;
    }


    protected function setEmbedded()
    {
        $brandHAL = $this->getNewHAL(BrandHAL::class,true);
        $this->dto->addEmbedded('brand', $brandHAL->getHAL($this->dto->getEntity()->getBrand()));
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
        $this->dto->addLink('self', [
            'href' => $this->router->generate('product_read', ['id' => $this->dto->getId()]),
            'method' => 'GET'
        ]);
    }

    private function updateLink()
    {
        $this->dto->addLink('update', [
            'href' => $this->router->generate('product_update', ['id' => $this->dto->getId()]),
            'method' => 'PATCH'
        ]);
    }

    private function replaceLink()
    {
        $this->dto->addLink('replace', [
            'href' => $this->router->generate('product_update', ['id' => $this->dto->getId()]),
            'method' => 'PUT'
        ]);
    }

    private function deleteLink()
    {
        $this->dto->addLink('delete', [
                'href' => $this->router->generate('product_update', ['id' => $this->dto->getId()]),
                'method' => 'DELETE'
        ]);
    }

    protected function setIndexLinks()
    {
        $this->dtoIndex->addLink('first', ['href' => '']);
        $this->dtoIndex->addLink('prev', ['href' => '']);
        $this->dtoIndex->addLink('self', ['href' => '']);
        $this->dtoIndex->addLink('next', ['href' => '']);
        $this->dtoIndex->addLink('last', ['href' => '']);
    }


    protected function setIndexEmbedded()
    {
        $this->dtoIndex->addEmbedded('products', $this->HalifyCollection($this->entityList, $this));
    }

    protected function setIndexPagination()
    {
        // TODO: Implement setIndexPagination() method.
    }
}