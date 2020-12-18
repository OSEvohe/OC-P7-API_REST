<?php

namespace App\Controller;

use App\Entity\Company;
use App\Exception\ApiCannotDeleteException;
use App\Form\CompanyType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\CompanyHAL;
use App\Service\ManageCompany;
use App\Service\ManageEntities;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * @Route ("/api")
 * @IsGranted("ROLE_SUPER_ADMIN", message="Access to companies denied")
 * @OA\Tag (name="Companies")
 * @OA\Response(response=403, description="Only Bilemo admin can manage companies")
 * @OA\Response (response="401", ref="#/components/responses/JWTTokenError")
 */
class CompanyController extends AbstractController
{
    /** @var CompanyHAL */
    private $companyHAL;

    /** @var ManageEntities */
    private $manageCompany;

    /**
     * CompanyController constructor.
     * @param CompanyHAL $companyHAL
     * @param ManageCompany $manageCompany
     */
    public function __construct(CompanyHAL $companyHAL, ManageCompany $manageCompany)
    {
        $this->companyHAL = $companyHAL;
        $this->manageCompany = $manageCompany;
    }


    /**
     * List companies
     * @Route("/companies/{page}/{limit}", name="companies_list", methods={"GET"}, requirements={"page"="\d*", "limit"="\d*"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     * @throws Exception
     *
     * @OA\Get(description="Return a list of companies")
     * @OA\Parameter (ref="#/components/parameters/pageNumber")
     * @OA\Parameter (ref="#/components/parameters/limit")
     * @OA\Response(response=200, ref="#/components/responses/listCompanies")
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $data = $this->manageCompany->list(Company::class,$page, $limit);
        return $this->json($this->companyHAL->getEntityListHAL($data, 'companies'), Response::HTTP_OK, [], ['groups' => ['list_companies', 'index']]);
    }


    /**
     * Show company's details
     * @Route("/company/{id}", name="company_read", methods={"GET"}, requirements={"id"="\d*"})
     * @param Company $company
     * @return JsonResponse
     *
     * @OA\Get(description="This resource represent a company, companies are managed by Bilemo admin")
     * @OA\Parameter (ref="#/components/parameters/id")
     * @OA\Response(response=200, ref="#/components/responses/readCompany")
     * @OA\Response(response=404, description="Company not found")
     * @OA\Response(response=400, ref="#/components/responses/BadParameters")
     */
    public function read(Company $company): JsonResponse
    {
        return $this->json($this->companyHAL->getHAL($company), Response::HTTP_OK, [], ['groups' => 'show_company']);
    }


    /**
     * Create a new company
     * @Route ("/company", name="company_create", methods={"POST"} )
     *
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @return JsonResponse
     *
     * @OA\Post(description="Create a new company")
     * @OA\Response(response=201, ref="#/components/responses/NewCompany")
     * @OA\Response(response=400, ref="#/components/responses/BadParameters")
     * @OA\RequestBody(ref="#/components/requestBodies/NewCompany")
     */
    public function create(Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageCompany->register($company);
        return $this->json($this->companyHAL->getHAL($company), Response::HTTP_CREATED, [], ['groups' => 'show_company']);
    }


    /**
     * Update a company
     * @Route ("/company/{id}", name="company_update", methods={"PUT", "PATCH"}, requirements={"id"="\d*"} )
     *
     * @param Company $company
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @return JsonResponse
     *
     * @OA\Parameter (ref="#/components/parameters/id")
     * @OA\Patch (description="**Update Company**, only fields present in body will be updated")
     * @OA\Put (description="**Update Company**, all fields are required")
     * @OA\Response(response=200, ref="#/components/responses/UpdateCompany")
     * @OA\Response(response=400, ref="#/components/responses/BadParameters")
     * @OA\RequestBody(ref="#/components/requestBodies/UpdateCompany")
     * @OA\Response(response=404, description="Company not found")
     */
    public function update(Company $company, Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        $form = $this->createForm(CompanyType::class, $company);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageCompany->update($company);
        return $this->json($this->companyHAL->getHAL($company), Response::HTTP_OK, [], ['groups' => 'show_company']);
    }


    /**
     * @Route ("/company/{id}", name="company_delete", methods={"DELETE"}, requirements={"id"="\d*"} )
     *
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return JsonResponse
     *
     * @OA\Delete(description="Delete a company")
     * @OA\Parameter (ref="#/components/parameters/id")
     * @OA\Response(response=200, description="Company deleted")
     * @OA\Response(response=400, ref="#/components/responses/BadParameters")
     * @OA\Response(response=409, description="Cannot delete, all users attached to this company must be deleted first")
     * @OA\Response(response=404, description="Company not found")
     */
    public function delete(Company $company, EntityManagerInterface $em): JsonResponse
    {
        $id = $company->getId();
        $this->manageCompany->deleteCompany($company);

        return $this->json(['message' => "Company #".$id." deleted!",Response::HTTP_OK]);
    }
}
