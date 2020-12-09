<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\BrandHAL;
use App\Service\ManageEntities;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    /** @var BrandHAL */
    private $brandHAL;

    /** @var ManageEntities */
    private $manageEntities;

    /**
     * BrandController constructor.
     * @param BrandHAL $brandHAL
     * @param ManageEntities $manageEntities
     */
    public function __construct(BrandHAL $brandHAL, ManageEntities $manageEntities)
    {
        $this->brandHAL = $brandHAL;
        $this->manageEntities = $manageEntities;
    }


    /**
     * List Brands
     * @Route("/brands/{page}/{limit}", name="brands_list", methods={"GET"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     * @throws Exception
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $data = $this->manageEntities->list(Brand::class,$page, $limit);
        return $this->json($this->brandHAL->getEntityListHAL($data, 'brands'), Response::HTTP_OK, [], ['groups' => ['list_brands', 'index']]);
    }


    /**
     * Show brand details
     * @Route("/brand/{id}", name="brand_read", methods={"GET"})
     * @param Brand $brand
     * @return JsonResponse
     */
    public function read(Brand $brand): JsonResponse
    {
        return $this->json($this->brandHAL->getHAL($brand), Response::HTTP_OK, [], ['groups' => ['show_brand']]);
    }


    /**
     * Create a new brand
     * @Route ("/brand", name="brand_create", methods={"POST"} )
     *
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function create(Request $request, FormHelper $formHelper, DataHelper $dataHelper,  EntityManagerInterface $em): JsonResponse
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($brand);
        return $this->json($this->brandHAL->getHAL($brand), Response::HTTP_CREATED, [], ['groups' => 'show_brand']);
    }


    /**
     * Update a brand
     * @Route ("/brand/{id}", name="brand_update", methods={"PUT", "PATCH"} )
     *
     * @param Brand $brand
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function update(Brand $brand, Request $request, FormHelper $formHelper, DataHelper $dataHelper, EntityManagerInterface $em): JsonResponse
    {
        $form = $this->createForm(BrandType::class, $brand);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($brand);
        return $this->json($this->brandHAL->getHAL($brand), Response::HTTP_OK, [], ['groups' => 'show_brand']);
    }


    /**
     * @Route ("/brand/{id}", name="brand_delete", methods={"DELETE"} )
     *
     * @param Brand $brand
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function delete(Brand $brand, EntityManagerInterface $em): JsonResponse
    {
        $id = $brand->getId();
        $this->manageEntities->delete($brand);

        return $this->json("Brand #".$id." deleted!",Response::HTTP_OK);
    }
}
