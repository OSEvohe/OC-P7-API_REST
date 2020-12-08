<?php


namespace App\Service\HAL;


use App\Dto\CompanyDto;


class CompanyHAL extends AbstractHAL
{

    protected function getDtoClass() : string
    {
        return CompanyDto::class;
    }


    protected function setEmbedded(): void
    {
        $this->setEmbeddedData($this->dto->getEntity()->getUsers(), UserHAL::class, 'users');
    }
}