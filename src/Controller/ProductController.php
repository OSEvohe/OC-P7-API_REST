<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\DataHelper;
use App\Service\FormHelper;
use App\Service\ManageProduct;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * ProductController constructor.
     * @param ManageProduct $manageProduct
     */
    public function __construct(ManageProduct $manageProduct)
    {
        $this->manageProduct = $manageProduct;
    }


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
        return $this->json($product, Response::HTTP_CREATED, [], ['groups' => 'show_product']);
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
        return $this->json($product, Response::HTTP_OK, [], ['groups' => 'show_product']);
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
