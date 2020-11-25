<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/api/products", name="products_list", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(Product::class);
        $products = $er->findAll();

        return $this->json($products, Response::HTTP_OK);
    }
}
