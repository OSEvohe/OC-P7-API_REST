<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Rollerworks\Component\PasswordStrength\Validator\Constraints\PasswordRequirements;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @UniqueEntity (fields = "name", message = "This company name is already used")
 */
class Company implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"show_company", "show_user", "list_company"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"show_company", "show_user", "list_company"})
     * @Assert\NotBlank(message="Company name is missing or empty")
     * @Assert\Length (
     *     min = 2,
     *     max = 100,
     *     minMessage = "Company name must be at least {{ limit }} characters long",
     *     maxMessage = "Company name cannot be longer than {{ limit }} characters",
     *     )
     */
    private $name;

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
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="company")
     * @Groups({"show_company"})
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCompany($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCompany() === $this) {
                $user->setCompany(null);
            }
        }

        return $this;
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
