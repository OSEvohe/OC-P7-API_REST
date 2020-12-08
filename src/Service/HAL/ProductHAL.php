<?php


namespace App\Service\HAL;


use App\Dto\ProductDto;


class ProductHAL extends AbstractHAL
{

    protected function getDtoClass() : string
    {
        return ProductDto::class;
    }


    protected function setEmbedded() : void
    {
        $this->setEmbeddedData($this->dto->getEntity()->getBrand(), BrandHAL::class, 'brand');
    }
}