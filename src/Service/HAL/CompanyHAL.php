<?php


namespace App\Service\HAL;


use App\Dto\CompanyDto;
use App\Entity\User;

class CompanyHAL extends AbstractHAL
{
    /** @var CompanyDto */
    protected $dto;


    protected function getDtoClass()
    {
        return CompanyDto::class;
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
            'href' => $this->router->generate('company_read', ['id' => $this->dto->getId()]),
            'method' => 'GET'
        ]);
    }

    private function updateLink()
    {
        $this->dto->addLink('update', [
            'href' => $this->router->generate('company_update', ['id' => $this->dto->getId()]),
            'method' => 'PATCH'
        ]);
    }

    private function replaceLink()
    {
        $this->dto->addLink('replace', [
            'href' => $this->router->generate('company_update', ['id' => $this->dto->getId()]),
            'method' => 'PUT'
        ]);
    }

    private function deleteLink()
    {
        $this->dto->addLink('delete', [
            'href' => $this->router->generate('company_update', ['id' => $this->dto->getId()]),
            'method' => 'DELETE'
        ]);
    }

    protected function setEmbedded()
    {
        $userHAL = new UserHAL($this->router, $this->security, true);
        $this->dto->addEmbedded('users', $this->HalifyCollection($this->dto->getEntity()->getUsers(), $userHAL));
    }

    protected function setIndexEmbedded()
    {
        // TODO: Implement setIndexEmbedded() method.
    }
}