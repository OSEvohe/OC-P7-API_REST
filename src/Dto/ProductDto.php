<?php


namespace App\Dto;


use App\Entity\Product;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

class ProductDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;


    /**
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    public function getId()
    {
        return $this->entity->getId();
    }


    /**
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    public function getName()
    {
        return $this->entity->getName();
    }


    /**
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    public function getPrice()
    {
        return $this->entity->getPrice();
    }


    /**
     * @Groups({"show_product"})
     */
    public function getDescription()
    {
        return $this->entity->getDescription();
    }


    /**
     * @Groups({"show_product"})
     */
    public function getImage()
    {
        return $this->entity->getImage();
    }


    /**
     * @SerializedName("_link")
     * @Groups({"show_brand", "show_product", "list_products"})
     */
    public function getLink()
    {
        return $this->_links;
    }

    /**
     * @Groups({"show_product", "list_products"})
     * @SerializedName("_embedded")
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}