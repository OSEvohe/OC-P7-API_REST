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
class User implements UserInterface
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
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"show_user", "list_users", "show_company"})
     * @Assert\NotBlank (message="Username is missing or empty")
     * @Assert\Regex(pattern="/^[a-z]+[a-z0-9]+$/i", message="Username must start by a letter and can only consist of alphanumeric characters")
     * @Assert\Length (
     *     min = 3,
     *     max = 30,
     *     minMessage = "Username must be at least {{ limit }} characters long",
     *     maxMessage = "Username cannot be longer than {{ limit }} characters",
     *     )
     * @Ass
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string $plainPassword the plain password, not stored
     * @UserPassword()
     * @Assert\NotBlank(message = "Password is missing or empty")
     * @Assert\Length(
     *     max = 255,
     *     maxMessage="Password cannot be longer than {{limit}} characters"
     * )
     * @PasswordRequirements(
     *      minLength = 8,
     *      tooShortMessage = "Password must be at least {{ limit }} characters long",
     *      requireLetters = true,
     *      missingLettersMessage = "Password must contains at least one letter",
     *      requireCaseDiff = true,
     *      requireCaseDiffMessage = "Password must contains lower and upper case letters",
     *      requireNumbers = true,
     *      missingNumbersMessage = "Password must contains at least one number",
     *      requireSpecialCharacter = true,
     *      missingSpecialCharacterMessage = "Password must contains at least one special character"
     * )
     */
    private $plainPassword;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }
}
