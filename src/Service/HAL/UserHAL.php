<?php


namespace App\Service\HAL;


use App\Dto\UserDto;
use App\Entity\Company;
use App\Entity\User;

class UserHAL extends AbstractHAL
{

    /**
     * @inheritDoc
     */
    protected function getDtoClass()
    {
        return UserDto::class;
    }


    protected function setEmbedded()
    {
        $companyHAL = new CompanyHAL($this->router, $this->security, true);
        $this->dto->addEmbedded([
            'company' => $companyHAL->getHAL($this->dto->getEntity()->getCompany())
        ]);
    }


    protected function setLinks()
    {
        $this->selfLink();
        if ($this->security->isGranted(User::USER_ADMIN) || $this->security->isGranted(User::USER_COMPANY_ADMIN)) {
            $this->updateLink();
            $this->replaceLink();
            $this->deleteLink();
        }
    }


    private function selfLink()
    {
        $this->dto->addLink(["self" =>
            [
                "href" => $this->router->generate("user_read", ['id' => $this->dto->getId()]),
                "method" => "GET"
            ]
        ]);
    }


    private function updateLink()
    {
        $this->dto->addLink(["update" =>
            [
                "href" => $this->router->generate("user_update", ['id' => $this->dto->getId()]),
                "method" => "PATCH"
            ]
        ]);
    }


    private function replaceLink()
    {
        $this->dto->addLink(["replace" =>
            [
                "href" => $this->router->generate("user_update", ['id' => $this->dto->getId()]),
                "method" => "PUT"
            ]
        ]);
    }


    private function deleteLink()
    {
        $this->dto->addLink(["delete" =>
            [
                "href" => $this->router->generate("user_update", ['id' => $this->dto->getId()]),
                "method" => "DELETE"
            ]
        ]);
    }
}