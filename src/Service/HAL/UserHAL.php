<?php


namespace App\Service\HAL;


use App\Dto\UserDto;
use App\Entity\User;


class UserHAL extends AbstractHAL
{

    protected function getDtoClass(): string
    {
        return UserDto::class;
    }


    protected function setEmbedded(): void
    {
        $this->setEmbeddedData($this->dto->getEntity()->getCompany(), CompanyHAL::class, 'company');
    }


    protected function setLinks()
    {
        $this->addLink('self', 'read', 'GET');
        if ($this->security->isGranted(User::USER_ADMIN)) {
            $this->addLink('update', 'update', 'PATCH');
            $this->addLink('replace', 'update', 'PUT');
            $this->addLink('delete', 'delete', 'DELETE');
        }
    }
}