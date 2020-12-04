<?php


namespace App\Dto;


use App\Entity\Company;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UserDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;

    /** @Groups({"show_user", "list_users", "show_company", "shortlist_users"}) */
    public function getId(): ?int
    {
        return $this->entity->getId();
    }


    /** @Groups({"show_user", "list_users", "show_company", "shortlist_users"}) */
    public function getUsername(): string
    {
        return (string)$this->entity->getUsername();
    }


    /** @Groups({"show_user"}) */
    public function getEmail(): ?string
    {
        return $this->entity->getEmail();
    }

    /** @Groups({"show_user"}) */
    public function getFirstName(): ?string
    {
        return $this->entity->getFirstName();
    }

    /** @Groups({"show_user"}) */
    public function getLastName(): ?string
    {
        return $this->entity->getLastName();
    }

    /**
     * @SerializedName("_link")
     * @Groups({"show_user", "list_users", "show_company"})
     */
    public function getLink()
    {
        return $this->_links;
    }

    /**
     * @SerializedName("_embedded")
     * @Groups({"show_user", "list_users"})
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}