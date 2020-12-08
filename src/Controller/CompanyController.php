<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\CompanyHAL;
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
    /**
     * @var CompanyHAL
     */
    private $companyHAL;

    /**
     * CompanyController constructor.
     * @param CompanyHAL $companyHAL
     */
    public function __construct(CompanyHAL $companyHAL)
    {
        $this->companyHAL = $companyHAL;
    }


    /**
     * List Company
     * @Route("/company/{page}/{limit}", name="company_list", methods={"GET"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(Company::class);
        $company =  $er->findBy([],[], $limit, ($page-1)*$limit);
        $count = $er->count([]);

        return $this->json($this->companyHAL->getEntityListHAL($company, $count), Response::HTTP_OK, [], ['groups' => 'list_company']);
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

        $em->persist($company);
        $em->flush();
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

        $em->persist($company);
        $em->flush();
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
        $em->remove($company);
        $em->flush();

        return $this->json("Brand #".$id." deleted!",Response::HTTP_OK);
    }
}
