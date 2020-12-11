<?php


namespace App\Dto;


use App\Entity\Company;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class UserDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;

    /** @Groups({"show_user", "list_users", "show_company"}) */
    public function getId(): ?int
    {
        return $this->entity->getId();
    }


    /** @Groups({"list_users", "show_user", "show_company"}) */
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
     * @SerializedName("_links")
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