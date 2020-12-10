<?php


namespace App\Security;


use App\Entity\Company;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const ACCESS_USER = 'access_user';

    protected function supports(string $attribute, $subject)
    {
        return (self::ACCESS_USER === $attribute) && ($subject instanceof User);
    }



    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        /** @var User $subject */

        /** @var Company $company */
        $company = $token->getUser();

        // if the Company is superadmin, always return true
        if (false !== array_search(Company::SUPER_ADMIN, $company->getRoles())){
            return true;
        }

        return $company->getId() === $subject->getCompany()->getId();
    }
}