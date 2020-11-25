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
        $user
            ->setUsername('user1')
            ->setEmail('user1@company.com')
            ->setFirstName('John')
            ->setLastName('Doe');
        $user->setCompany($company);
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'PassWord01!'));
        $user->setRoles([User::USER_COMPANY_ADMIN]);
        $manager->persist($user);

        $admin = new User();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@bilemo.com')
            ->setFirstName('Sebastien')
            ->setLastName('Admin');
        $admin->setCompany($bilemo);
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'superPassword34!'));
        $admin->setRoles([User::USER_ADMIN]);
        $manager->persist($admin);

        $manager->flush();
    }
}