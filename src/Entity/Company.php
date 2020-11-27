<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @UniqueEntity (fields = "name", message = "This company name is already used")
 */
class Company
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
}
