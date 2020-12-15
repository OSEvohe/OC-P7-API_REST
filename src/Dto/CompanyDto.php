<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use OpenApi\Annotations as OA;

class CompanyDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;


    /** @Groups({"show_company", "list_companies", "show_user", "list_users"}) */
    public function getId() : int
    {
        return $this->entity->getId();
    }


    /** @Groups({"show_company", "list_companies", "show_user", "list_users"}) */
    public function getName() : string
    {
        return $this->entity->getName();
    }


    /** @Groups({"show_company", "list_companies"}) */
    public function getUsername(): string
    {
        return (string)$this->entity->getUsername();
    }


    /**
     * @SerializedName("_links")
     * @Groups({"list_companies", "show_company", "show_user", "list_users"})
     *
     * @OA\Property(ref="#/components/schemas/_links")
     */
    public function getLinks()
    {
        return $this->_links;
    }

    /**
     * @Groups({"show_company"})
     * @SerializedName("_embedded")
     *
     * @OA\Property(
     *      type="object",
     *     @OA\Property (
     *     property="users",
     *      @OA\Items(ref="#/components/schemas/CompanyUsers")
     *     )
     * )
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}