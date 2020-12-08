<?php


namespace App\Service\HAL;


use App\Dto\BrandDto;


class BrandHAL extends AbstractHAL
{

    protected function getDtoClass(): string
    {
        return BrandDto::class;
    }


    protected function setEmbedded(): void
    {
        $this->setEmbeddedData($this->dto->getEntity()->getProducts(), ProductHAL::class, 'products');
    }
}