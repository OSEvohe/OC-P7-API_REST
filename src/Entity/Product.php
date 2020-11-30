<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity(fields = "name", message = "This phone name is already used")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_products", "show_product", "show_brand"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"list_products", "show_product", "show_brand"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 2,
     *     max = 100,
     *     minMessage = "Phone name must be at least {{ limit }} characters long",
     *     maxMessage = "Phone name cannot be longer than {{ limit }} characters",
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=7, scale=2)
     * @Groups({"list_products", "show_product", "show_brand"})
     * @Assert\NotBlank
     * @Assert\Regex(pattern = "/^\d+([.]\d{1,2})?$/", message="Price can containt up to 2 decimal digits with '.' as decimal separator")
     * @Assert\Range(
     *      min = 1,
     *      max = 99999.99,
     *      notInRangeMessage = "Price must be between {{ min }} and {{ max }}",
     * )
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Groups({"show_product"})
     * @Assert\NotBlank
     * @Assert\Length (
     *     min = 10,
     *     max = 65535,
     *     minMessage = "Phone description must be at least {{ limit }} characters long",
     *     maxMessage = "Phone description cannot be longer than {{ limit }} characters",
     *     )
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"show_product"})
     * @Assert\Length (
     *     min = 5,
     *     max = 255,
     *     minMessage = "Image path must be at least {{ limit }} characters long",
     *     maxMessage = "Image path cannot be longer than {{ limit }} characters",
     *     )
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Brand::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_products", "show_product"})
     * @Assert\NotBlank
     */
    private $brand;

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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
