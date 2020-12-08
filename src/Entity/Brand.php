<?php

namespace App\Entity;

use App\Repository\BrandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BrandRepository::class)
 * @UniqueEntity (fields = "name", message="This brand name is already used")
 */
class Brand
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_products", "show_product", "show_brand", "list_brands"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"list_products", "show_product", "show_brand", "list_brands"})
     * @Assert\NotBlank(message = "Brand name is missing or empty")
     * @Assert\Length (
     *     min = 2,
     *     max = 100,
     *     minMessage = "Brand name must be at least {{ limit }} characters long",
     *     maxMessage = "Brand name cannot be longer than {{ limit }} characters",
     *     )
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="brand")
     * @Groups({"show_brand"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
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
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setBrand($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getBrand() === $this) {
                $product->setBrand(null);
            }
        }

        return $this;
    }
}
