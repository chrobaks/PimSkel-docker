<?php

namespace App\Controller;

use App\Form\ProductType;
use Exception;
use Pimcore\Model\DataObject\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\ProductService;

class ProductController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/product', name: 'product_index')]
    public function indexAction(Request $request, ProductService $ProductService): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        try {
            if ($request->isMethod('POST')) {
                $form->handleRequest($request);
                if (!$ProductService->addProduct($product, $form)) {
                    throw new Exception($ProductService->getError());
                }
            }
        } catch (Exception $e) {
            return new Response('An error occurred: '.$e->getMessage());
        }

        $products = new Product\Listing();

        return $this->render('product/index.html.twig', [
            'products' => $products->getObjects(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param ProductService $ProductService
     * @param int $id
     * @return Response
     */
    #[Route('/product/update/{id}', name: 'product_update')]
    public function updateAction(Request $request, ProductService $ProductService, int $id): Response
    {
        try {
            $product = Product::getById($id);
            $form = $this->createForm(ProductType::class, $product);
            if (!$product) { // Check if the product exists
                throw new Exception('Product not found.');
            }
            if ($request->isMethod('POST')) { // Form method must be POST
                $form->handleRequest($request);
                if ( ! $ProductService->addProduct($product, $form, $id)) {
                    throw new Exception($ProductService->getError());
                }
            } else {
                throw new Exception('Form is no post request.');
            }

            return new Response('Product updateAction successfully!', Response::HTTP_OK);
        } catch (Exception $e) {
            return new Response('Failed to updated product: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param int $id
     * @return Response
     */
    #[Route('/product/edit/{id}', name: 'product_edit')]
    public function editAction(int $id): Response
    {
        try {
            // Load the product object by ID
            $product = Product::getById($id);
            $form = $this->createForm(ProductType::class, $product);

            // Check if the product exists
            if (!$product) {
                throw new Exception('Product not found.');
            }
            $view = $this->renderView('product/form.update.html.twig', [
                'product' => $product,
                'form' => $form->createView(),
            ]);

            return new Response($view, Response::HTTP_OK);

        } catch (Exception $e) {
            // Return error response
            return new Response('Failed to update product: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @return Response
     */
    #[Route('/product/delete/{id}', name: 'product_delete')]
    public function deleteAction($id): Response
    {
        try {
            // Load the product object by ID
            $product = Product::getById($id);

            // Check if the product exists
            if (!$product) {
                throw new Exception('Product not found.');
            }

            // Delete the product
            $product->delete();

            // Return success response
            return new Response('Product deleted successfully.', Response::HTTP_OK);
        } catch (\Exception $e) {
            // Return error response
            return new Response('Failed to delete product: ' . $e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
}
