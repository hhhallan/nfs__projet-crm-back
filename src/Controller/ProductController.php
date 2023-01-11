<?php

namespace App\Controller;

use App\Service\Core\IProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    private readonly IProductService $productService;
    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }


    #[Route('/product', name: 'app_product_list', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json($this->productService->getAll());
    }
}
