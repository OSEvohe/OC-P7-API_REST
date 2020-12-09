<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\CompanyHAL;
use App\Service\ManageEntities;
use Doctrine\ORM\EntityManagerInterface;
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
    /** @var CompanyHAL */
    private $companyHAL;

    /** @var ManageEntities */
    private $manageEntities;

    /**
     * CompanyController constructor.
     * @param CompanyHAL $companyHAL
     * @param ManageEntities $manageEntities
     */
    public function __construct(CompanyHAL $companyHAL, ManageEntities $manageEntities)
    {
        $this->companyHAL = $companyHAL;
        $this->manageEntities = $manageEntities;
    }


    /**
     * List Company
     * @Route("/companies/{page}/{limit}", name="company_list", methods={"GET"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     * @throws \Exception
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $data = $this->manageEntities->list(Company::class,$page, $limit);
        return $this->json($this->companyHAL->getEntityListHAL($data, 'companies'), Response::HTTP_OK, [], ['groups' => ['list_companies', 'index']]);
    }


    /**
     * Show company details
     * @Route("/company/{id}", name="company_read", methods={"GET"})
     * @param Company $company
     * @return JsonResponse
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
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function create(Request $request, FormHelper $formHelper, DataHelper $dataHelper, EntityManagerInterface $em): JsonResponse
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($company);
        return $this->json($this->companyHAL->getHAL($company), Response::HTTP_CREATED, [], ['groups' => 'show_company']);
    }


    /**
     * Update a company
     * @Route ("/company/{id}", name="company_update", methods={"PUT", "PATCH"} )
     *
     * @param Company $company
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function update(Company $company, Request $request, FormHelper $formHelper, DataHelper $dataHelper, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(CompanyType::class, $company);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($company);
        return $this->json($this->companyHAL->getHAL($company), Response::HTTP_OK, [], ['groups' => 'show_company']);
    }


    /**
     * @Route ("/company/{id}", name="company_delete", methods={"DELETE"} )
     *
     * @param Company $company
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function delete(Company $company, EntityManagerInterface $em): JsonResponse
    {
        $id = $company->getId();
        $this->manageEntities->delete($company);

        return $this->json("Brand #".$id." deleted!",Response::HTTP_OK);
    }
}
