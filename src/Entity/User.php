<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity (fields="username", message="This username is already used")
 * @UniqueEntity (fields="email", message="This email address is already used")
 */
class User
{
    const USER_COMPANY_ADMIN = 'ROLE_COMPANY_ADMIN';
    const USER_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_user", "list_users", "show_company"})
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show_user"})
     * @Assert\NotBlank(message= "Email is missing or empty")
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"show_user"})
     * @Assert\NotBlank(message="Firstname is missing or empty")
     * @Assert\Length (
     *     min = 5,
     *     max = 100,
     *     minMessage = "First name must be at least {{ limit }} characters long",
     *     maxMessage = "First name cannot be longer than {{ limit }} characters",
     *     )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"show_user"})
     * @Assert\NotBlank(message="Lastname is missing or empty")
     * @Assert\Length (
     *     min = 5,
     *     max = 100,
     *     minMessage = "Last name must be at least {{ limit }} characters long",
     *     maxMessage = "Last name cannot be longer than {{ limit }} characters",
     *     )
     */
    private $lastName;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"show_user"})
     * @Assert\NotBlank(message="Company is missing or empty")
     */
    private $company;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }
}
