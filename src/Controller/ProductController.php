<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\HAL\ProductHAL;
use App\Service\ManageProduct;
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
     * @var ManageProduct
     */
    private $manageProduct;
    /**
     * @var ProductHAL
     */
    private $productHAL;


    /**
     * ProductController constructor.
     * @param ManageProduct $manageProduct
     * @param ProductHAL $productHAL
     */
    public function __construct(ManageProduct $manageProduct, ProductHAL $productHAL)
    {
        $this->manageProduct = $manageProduct;
        $this->productHAL = $productHAL;
    }


    /**
     * @Route("/products/", name="products_list", methods={"GET"})
     * @param int $page
     * @param int $limit
     * @return JsonResponse
     */
    public function index(int $page = 1, int $limit = 10): JsonResponse
    {
        $er = $this->getDoctrine()->getRepository(Product::class);
        $products = $er->findBy([],[], $limit, ($page-1)*$limit);
        $count = $er->count([]);

        return $this->json($this->productHAL->getEntityListHAL($products, $count), Response::HTTP_OK, [], ['groups' => ['list_products', 'index']]);
    }


    /**
     * @Route ("/product/{id}", name="product_read", methods={"GET"})
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
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageProduct->create($product);
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
        $form = $this->createForm(ProductType::class, $product);

      if (false === $formHelper->validate($form, $dataHelper->jsonDecode($request->getContent()), $request->isMethod("PUT"))) {
            return $formHelper->errorsResponse($form);
        }

        $this->manageProduct->update($product);
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
        $id = $product->getId();
        $this->manageProduct->delete($product);

        return $this->json("Product #".$id." deleted!",Response::HTTP_OK);
    }
}
