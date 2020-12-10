<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Product;
use App\Form\ProductType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\ProductHAL;
use App\Service\ManageEntities;
use Exception;
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
    /** @var ManageEntities */
    private $manageEntities;

    /** @var ProductHAL */
    private $productHAL;


    /**
     * @param ManageEntities $manageProduct
     * @param ProductHAL $productHAL
     */
    public function __construct(ManageEntities $manageProduct, ProductHAL $productHAL)
    {
        $this->manageEntities = $manageProduct;
        $this->productHAL = $productHAL;
    }


    /**
     * @Route("/products/{page}/{limit}", name="products_list", methods={"GET"})
     *
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     * @throws Exception
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $data = $this->manageEntities->list(Product::class,$page, $limit);
        return $this->json($this->productHAL->getEntityListHAL($data, 'products'), Response::HTTP_OK, [], ['groups' => ['list_products', 'index']]);
    }


    /**
     * @Route ("/product/{id}", name="product_read", methods={"GET"})
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function read(Product $product): JsonResponse
    {
        return $this->json($this->productHAL->getHAL($product), Response::HTTP_OK, [], ['groups' => 'show_product']);
    }


    /**
     * @Route ("/product", name="product_create", methods={"POST"} )
     *
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @return JsonResponse
     */
    public function create(Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        $this->denyAccessUnlessGranted(Company::SUPER_ADMIN, null, 'You are not allowed to create a new product');

        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($product);
        return $this->json($this->productHAL->getHAL($product), Response::HTTP_CREATED, [], ['groups' => 'show_product']);
    }


    /**
     * @Route ("/product/{id}", name="product_update", methods={"PUT", "PATCH"} )
     *
     * @param Product $product
     * @param Request $request
     * @param FormHelper $formHelper
     * @param DataHelper $dataHelper
     * @return JsonResponse
     */
    public function update(Product $product, Request $request, FormHelper $formHelper, DataHelper $dataHelper): JsonResponse
    {
        $this->denyAccessUnlessGranted(Company::SUPER_ADMIN, null, 'You are not allowed to modify a product');

        $form = $this->createForm(ProductType::class, $product);

      if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageEntities->save($product);
        return $this->json($this->productHAL->getHAL($product), Response::HTTP_OK, [], ['groups' => 'show_product']);
    }




    /**
     * @Route ("/product/{id}", name="product_delete", methods={"DELETE"} )
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function delete(Product $product): JsonResponse
    {
        $this->denyAccessUnlessGranted(Company::SUPER_ADMIN, null, 'You are not allowed to delete a product');

        $id = $product->getId();
        $this->manageEntities->delete($product);

        return $this->json("Product #".$id." deleted!",Response::HTTP_OK);
    }
}
