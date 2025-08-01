<?php

namespace App\Product\Web\Controller;

use App\Product\Application\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/api/products', name: 'app_product')]
final class ProductController extends AbstractController
{
    private ProductService $productService;
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    #[Route('', name: 'app_product', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $products = $this->productService->getAllProducts();

        return $this->json([
            'products' => $products->getData(),
        ], Response::HTTP_OK);
    }
}
