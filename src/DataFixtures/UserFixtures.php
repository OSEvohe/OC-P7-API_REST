<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;


    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $company = new Company();
        $company->setName('Company_test');
        $manager->persist($company);

        $bilemo = new Company();
        $bilemo->setName('Bilemo');
        $manager->persist($bilemo);

        $user = new User();
        $user->setUsername('user1');
        $user->setCompany($company);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRoles([User::USER_COMPANY_ADMIN]);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('admin');
        $user->setCompany($bilemo);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));
        $user->setRoles([User::USER_ADMIN]);
        $manager->persist($user);

        $manager->flush();
    }
}