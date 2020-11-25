<?php

namespace App\Controller;

use App\Entity\Company;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/api")
 */
class CompanyController extends AbstractController
{
    /**
     * List Company
     * @Route("/company", name="company_list", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(Company::class);
        $company = $er->findAll();

        return $this->json($company, Response::HTTP_OK, [], ['groups' => 'list_company']);
    }


    /**
     * Show company details
     * @Route("/company/{id}", name="company_read", methods={"GET"})
     * @param Company $company
     * @return JsonResponse
     */
    public function read(Company $company): JsonResponse
    {
        return $this->json($company, Response::HTTP_OK, [], ['groups' => 'show_company']);
    }


    /**
     * Create a new company
     * @Route ("/company", name="company_create", methods={"POST"} )
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return new JsonResponse(["TODO" => "Create a new company in database"]);
    }


    /**
     * Update a company
     * @Route ("/company/{id}", name="company_update", methods={"PUT", "PATCH"} )
     *
     * @param Company $company
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Company $company, Request $request): JsonResponse
    {
        return new JsonResponse(["TODO" => "Update company in database"]);
    }


    /**
     * @Route ("/company/{id}", name="company_delete", methods={"DELETE"} )
     *
     * @param Company $company
     * @return JsonResponse
     */
    public function delete(Company $company): JsonResponse
    {
        return new JsonResponse(["TODO" => 'delete company from database']);
    }
}
