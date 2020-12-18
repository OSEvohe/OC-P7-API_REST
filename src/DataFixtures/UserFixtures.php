<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture implements FixtureGroupInterface
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
        $company
            ->setName('Company_test')
            ->setUsername('user1');
        $company->setPassword($this->passwordEncoder->encodePassword($company, 'PassWord01!'));
        $manager->persist($company);

        $bilemo = new Company();
        $bilemo
            ->setName('Bilemo')
            ->setUsername('admin');
        $bilemo->setPassword($this->passwordEncoder->encodePassword($bilemo, 'superPassword34!'));
        $bilemo->setRoles([Company::SUPER_ADMIN]);


        $manager->persist($bilemo);

        $user = new User();
        $user->setEmail('user1@companyTest.com')
            ->setFirstName('John')
            ->setLastName('Doe');
        $user->setCompany($company);
        $manager->persist($user);

        $user2 = new User();
        $user2
            ->setEmail('admin@bilemo.com')
            ->setFirstName('Sebastien')
            ->setLastName('Bilemo');
        $user2->setCompany($company);
        $manager->persist($user2);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['starting_users'];
    }
}