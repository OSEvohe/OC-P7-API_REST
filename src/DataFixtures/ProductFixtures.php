<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $apple = new Brand();
        $apple->setName("Apple");
        $manager->persist($apple);

        $samsung = new Brand();
        $samsung->setName("Samsung");
        $manager->persist($samsung);

        for ($i=5;$i<13;$i++){
            $product = new Product();
            $product->setName("Iphone ". $i);
            $product->setPrice((string)(500+50*$i));
            $product->setDescription("Le ".$product->getName());
            $product->setBrand($apple);
            $manager->persist($product);
        }
        for ($i=5;$i<13;$i++){
            $product = new Product();
            $product->setName("Galaxy S". $i);
            $product->setPrice((string)(500+50*$i));
            $product->setDescription("Le ".$product->getName());
            $product->setBrand($samsung);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
