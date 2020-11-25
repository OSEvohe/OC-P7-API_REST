<?php

namespace App\Controller;

use App\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/api")
 */
class BrandController extends AbstractController
{
    /**
     * List Brands
     * @Route("/brands", name="brands_list", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(Brand::class);
        $brands = $er->findAll();

        return $this->json($brands, Response::HTTP_OK, [], ['groups' => ['list_brands']]);
    }


    /**
     * Show brand details
     * @Route("/brand/{id}", name="brand_read", methods={"GET"})
     * @param Brand $brand
     * @return JsonResponse
     */
    public function read(Brand $brand): JsonResponse
    {
        return $this->json($brand, Response::HTTP_OK, [], ['groups' => ['show_brand']]);
    }


    /**
     * Create a new brand
     * @Route ("/brand", name="brand_create", methods={"POST"} )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse(["TOTO" => "Create a new brand in database"]);
    }


    /**
     * Update a brand
     * @Route ("/brand/{id}", name="brand_update", methods={"PUT", "PATCH"} )
     *
     * @param Brand $brand
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Brand $brand, Request $request): JsonResponse
    {
        return new JsonResponse(["TOTO" => "Update brand in database"]);
    }


    /**
     * @Route ("/brand/{id}", name="brand_delete", methods={"DELETE"} )
     *
     * @param Brand $brand
     * @return JsonResponse
     */
    public function delete(Brand $brand): JsonResponse
    {
        return new JsonResponse(["TOTO" => 'delete brand from database']);
    }
}
