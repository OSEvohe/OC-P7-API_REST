<?php

namespace App\Controller;

use App\Dto\BrandDto;
use App\Entity\Brand;
use App\Entity\Company;
use App\Exception\ApiCannotDeleteException;
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
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

/**
 * @Route ("/api")
 *
 * @OA\Tag(name="Brand")
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
     * List Phone brands
     * @Route("/brands/{page}/{limit}", name="brands_list", methods={"GET"})
     *
     * @param int $page;
     * @param int $limit
     * @return JsonResponse
     * @throws Exception
     *
     * @OA\Parameter (ref="#/components/parameters/pageNumber")
     * @OA\Parameter (ref="#/components/parameters/limit")
     *
     * @OA\Response(
     *     response=200,
     *     description="Return list of brands",
     *     @OA\JsonContent(
     *       ref= "#/components/schemas/Brands")
     *     )
     * )
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
     *
     * @OA\Response(
     *     response=200,
     *     description="Return details of a phone brand",
     *     @OA\JsonContent(
     *       ref=@Model(type=BrandDto::class, groups={"show_brand"})
     *     )
     * )
     * @OA\Response(
     *     response=404,
     *     description="Brand not found",
     * )
     * @OA\Response(
     *     response=400,
     *     description="Bad Url Parameter, Brand id must be an integer"
     * )
     *
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
     * @return JsonResponse
     *
     * @OA\Response(response=403, description="You are not allowed to create a new brand")
     * @OA\Response(
     *     response=201,
     *     description="New brand created",
     *     @OA\JsonContent(
     *       ref=@Model(type=BrandDto::class, groups={"show_brand"})
     *      )
     * )
     *
     * @OA\Response(response=400, ref="#/components/responses/badParameters")     *
     * @OA\RequestBody(ref="#/components/requestBodies/NewBrand")
     */
    public function create(Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        $this->denyAccessUnlessGranted(Company::SUPER_ADMIN, null, 'You are not allowed to create a new brand');

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
     *
     * @OA\Parameter (name="id", in="path", description="Brand id", @OA\Schema (type="integer"))
     * @OA\Patch (description="**Update Brand**, only fields present in body will be updated")
     * @OA\Put (description="**Update Brand**, all fields are required")
     * @OA\Response(response=403, description="You are not allowed to modify a brand")
     * @OA\Response(
     *     response=200,
     *     description="Brand updated",
     *     @OA\JsonContent(
     *       ref=@Model(type=BrandDto::class, groups={"show_brand"})
     *      )
     * )
     * @OA\Response(response=404, description="Brand not found")
     * @OA\Response(response=400, ref="#/components/responses/badParameters")
     * @OA\RequestBody(ref="#/components/requestBodies/UpdateBrand")
     */
    public function update(Brand $brand, Request $request, FormHelper $formHelper, DataHelper $dataHelper, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted(Company::SUPER_ADMIN, null, 'You are not allowed to modify a brand');

        $form = $this->createForm(BrandType::class, $brand);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($brand);
        return $this->json($this->brandHAL->getHAL($brand), Response::HTTP_OK, [], ['groups' => 'show_brand']);
    }


    /**
     * Delete a brand
     * @Route ("/brand/{id}", name="brand_delete", methods={"DELETE"} )
     *
     * @param Brand $brand
     * @param EntityManagerInterface $em
     * @return JsonResponse
     *
     * @OA\Parameter (name="id", in="path", description="Brand id", @OA\Schema (type="integer"))
     * @OA\Response(response=200, description="Brand deleted")
     * @OA\Response(response=409, description="Cannot delete Brand, all products attached to this brand must be deleted first")
     * @OA\Response(response=400, ref="#/components/responses/badParameters")
     * @OA\Response(response=404, description="Brand not found")
     */
    public function delete(Brand $brand, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted(Company::SUPER_ADMIN, null, 'You are not allowed to delete a brand');

        if (0 < $brand->getProducts()->count()){
            throw new ApiCannotDeleteException("Cannot delete Brand, all products attached to this brand must be deleted first");
        }

        $id = $brand->getId();
        $this->manageEntities->delete($brand);

        return $this->json(['message' => "Brand #".$id." deleted!",Response::HTTP_OK]);
    }
}
