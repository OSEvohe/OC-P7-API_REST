<?php


namespace App\Dto;


use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use OpenApi\Annotations as OA;

class ProductDto extends AbstractDto
{
    use LinksTrait;
    use EmbeddedTrait;


    /**
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    public function getId() : int
    {
        return $this->entity->getId();
    }


    /**
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    public function getName() : string
    {
        return $this->entity->getName();
    }


    /**
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    public function getPrice() : string
    {
        return $this->entity->getPrice();
    }


    /**
     * @Groups({"show_product"})
     */
    public function getDescription() : string
    {
        return $this->entity->getDescription();
    }


    /**
     * @SerializedName("_links")
     * @Groups({"show_brand", "show_product", "list_products"})
     *
     * @OA\Property(ref="#/components/schemas/_links")
     */
    public function getLink()
    {
        return $this->_links;
    }

    /**
     * @Groups({"show_product", "list_products"})
     * @SerializedName("_embedded")
     *
     * @OA\Property(
     *      type="object",
     *     @OA\Property (
     *     property="brand",
     *     ref="#/components/schemas/BrandsIndex"
     *     )
     * )
     */
    public function getEmbedded()
    {
        return $this->_embedded;
    }
}