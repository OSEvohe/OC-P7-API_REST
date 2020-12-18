<?php


namespace App\Service;


use App\Entity\Company;
use App\Entity\User;
use App\Exception\ApiCannotDeleteException;
use App\Exception\ApiDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;

class ManageCompany extends ManageEntities
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;
    /** @var Security */
    private $security;


    /**
     * @Required
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param Security $security
     */
    public function setServices(UserPasswordEncoderInterface $passwordEncoder, Security $security)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->security = $security;
    }


    public function register(Company $company)
    {
        $this->updatePassword($company);
    }

    public function update(Company $company){
        if ($company->getPlainPassword()){
            $this->updatePassword($company);
        } else {
            $this->save($company);
        }
    }


    public function updatePassword(Company $company){
        $company->setPassword($this->passwordEncoder->encodePassword(
            $company,
            $company->getPlainPassword()
        ));

        $this->save($company);
    }

    public function listUsers($page = 1, $limit = 10): array
    {
        if ($this->security->isGranted(Company::SUPER_ADMIN)){
            return parent::list(User::class, $page, $limit);
        }

        $er = $this->em->getRepository(User::class);
        $list = $er->findBy(['company' => $this->security->getUser()], [], $limit, ($page - 1) * $limit);
        $count = $er->count([]);

        return ['list' => $list, 'count' => $count];
    }

    public function createUser(User $user)
    {
        if ($this->security->isGranted(Company::SUPER_ADMIN)){
            throw new ApiDeniedException("Bilemo Admin cannot create users");
        }

        parent::save($user);
    }


    public function deleteCompany(Company $company)
    {
        if (0 < $company->getUsers()->count()){
            throw new ApiCannotDeleteException("Cannot delete Company, all users attached to this company must be deleted first");
        }

        if ($this->security->getUser()->getId() == $company->getiD()){
            throw new ApiDeniedException("Admin cannot delete itself");
        }

        parent::delete($company);
    }
}