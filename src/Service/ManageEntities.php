<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class ManageEntities
{
    /** @var EntityManagerInterface */
    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function save($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;

    }

    public function delete($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    public function list($className, $page = 1, $limit = 10): array
    {
        $er = $this->em->getRepository($className);
        $list = $er->findBy([], [], $limit, ($page - 1) * $limit);
        $count = $er->count([]);

        return ['list' => $list, 'count' => $count];
    }


}