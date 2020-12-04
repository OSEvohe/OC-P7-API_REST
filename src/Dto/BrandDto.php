<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class BrandDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;


    /**
     * @Groups({"list_products", "show_product", "show_brand", "list_brands"})
     */
    public function getId(): int
    {
        return $this->entity->getId();
    }


    /**
     * @Groups({"list_products", "show_product", "show_brand", "list_brands"})
     */
    public function getName(): string
    {
        return $this->entity->getName();
    }

    /**
     * @SerializedName("_links")
     * @Groups({"show_brand", "show_product"})
     */
    public function getLinks()
    {
        return $this->_links;
    }

    /**
     * @Groups({"show_brand"})
     * @SerializedName("_embedded")
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}