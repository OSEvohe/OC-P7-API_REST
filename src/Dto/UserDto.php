<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use OpenApi\Annotations as OA;

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
     * @OA\Property(ref="#/components/schemas/_links")
     */
    public function getLink()
    {
        return $this->_links;
    }

    /**
     * @SerializedName("_embedded")
     * @Groups({"show_user", "list_users"})
     *
     * @OA\Property(
     *      type="object",
     *     @OA\Property (
     *     property="company",
     *     ref="#/components/schemas/CompaniesIndex"
     *     )
     * )
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}