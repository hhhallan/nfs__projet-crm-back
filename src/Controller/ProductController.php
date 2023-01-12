<?php

namespace App\Controller;

use App\Service\AuthService;
use App\Service\Core\IProductService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProductController extends AbstractController
{
    private readonly IProductService $productService;
    private readonly AuthService $authService;

    public function __construct(IProductService $productService, AuthService $authService)
    {
        $this->productService = $productService;
        $this->authService = $authService;
    }

    #[Route('/product', name: 'app_product_list', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json($this->productService->getAll());
    }

    #[Route('/product/archived', name: 'app_product_list_archived', methods: 'GET')]
    public function archived(): JsonResponse
    {
        return $this->json($this->productService->getArchived());
    }

    #[Route('/product/{id}', name: 'app_product_read', methods: 'GET')]
    public function read(string $id): JsonResponse
    {
        try {
            return $this->json($this->productService->read($id));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }

    #[Route('/product', name: 'app_product_create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        try {
            $body = json_decode($request->getContent(), true);
            return $this->json($this->productService->create($body));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }

    #[Route('/product/{id}', name: 'app_product_update', methods: 'PUT')]
    public function update(string $id, Request $request): JsonResponse
    {
        try {
            $body = json_decode($request->getContent(), true);
            return $this->json($this->productService->update($id, $body));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }

    #[Route('/product/{id}', name: 'app_product_remove', methods: 'DELETE')]
    public function remove(string $id): JsonResponse
    {
        try {
            return $this->json($this->productService->delete($id));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() ?? 400);
        }
    }
}
