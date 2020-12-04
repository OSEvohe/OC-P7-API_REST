<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class CompanyDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;


    /** @Groups({"show_company", "list_company", "show_user", "list_users"}) */
    public function getId()
    {
        return $this->entity->getId();
    }


    /** @Groups({"show_company", "list_company", "show_user", "list_users"}) */
    public function getName()
    {
        return $this->entity->getName();
    }


    /**
     * @SerializedName("_links")
     * @Groups({"list_company", "show_company", "show_user", "list_users"})
     */
    public function getLinks()
    {
        return $this->_links;
    }

    /**
     * @Groups({"show_company"})
     * @SerializedName("_embedded")
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}