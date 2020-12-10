<?php


namespace App\Service;


use App\Entity\Company;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ManageCompany extends ManageEntities
{
    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;


    /**
     * @Required
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function setUserPasswordEncoder(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
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
}