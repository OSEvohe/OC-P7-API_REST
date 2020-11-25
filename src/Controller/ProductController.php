<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route ("/api")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products_list", methods={"GET"})
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(Product::class);
        $products = $er->findAll();

        return $this->json($products, Response::HTTP_OK, [], ['groups' => 'list_products']);
    }


    /**
     * @Route ("/product/{id}", name="product_read", methods={"GET"})
     * @param Product $product
     * @return JsonResponse
     */
    public function read(Product $product): JsonResponse
    {
        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'show_product']);
    }


    /**
     * @Route ("/product", name="product_create", methods={"POST"} )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse(["TODO" => "Create a new product in database"]);
    }


    /**
     * @Route ("/product/{id}", name="product_update", methods={"PUT", "PATCH"} )
     *
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Product $product, Request $request): JsonResponse
    {
        return new JsonResponse(["TODO" => "Update product in database"]);
    }


    /**
     * @Route ("/product/{id}", name="product_delete", methods={"DELETE"} )
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function delete(Product $product): JsonResponse
    {
        return new JsonResponse(["TODO" => 'delete product from database']);
    }
}
