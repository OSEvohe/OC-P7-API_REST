<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ManageProduct
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ProductRepository
     */
    private $repository;


    public function __construct(EntityManagerInterface $em, ProductRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }


    public function save(Product $product)
    {
        $this->em->persist($product);
        $this->em->flush();

        return $product;
    }


    public function delete(Product $product)
    {
        $this->em->remove($product);
        $this->em->flush();
    }


    public function update($product)
    {
        if ($this->exist($product)) {
            return $this->save($product);
        }
        return false;
    }


    /** Create a new product
     * @param $product
     * @return Product|false
     */
    public function create($product)
    {
        if (!$this->exist($product)) {
            return $this->save($product);
        }
        return false;
    }


    /**
     * Check if $product is a persisted object
     * @param $product
     * @return bool
     */
    private function exist($product)
    {
        return ($this->em->contains($product));
    }
}