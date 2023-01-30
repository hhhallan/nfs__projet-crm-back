<?php

namespace App\Controller;

use App\Service\Core\IDevisService;
use App\Service\Core\IFactureService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class FactureController extends AbstractController
{
    private readonly IFactureService $factureService;
    public function __construct(IFactureService $factureService)
    {
        $this->factureService = $factureService;
    }

    #[Route('/facture', name: 'app_facture_list', methods: 'GET')]
    public function list(): JsonResponse
    {
        return $this->json($this->factureService->getAll());
    }

    #[Route('/facture/commercial/{commercialId}', name: 'app_facture_commercial', methods: 'GET')]
    public function getByCommercial(string $commercialId): JsonResponse
    {
        try {
            return $this->json($this->factureService->getByCommercial($commercialId));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/facture/client/{clientId}', name: 'app_facture_client', methods: 'GET')]
    public function getByClient(string $clientId): JsonResponse
    {
        try {
            return $this->json($this->factureService->getByClient($clientId));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()],  $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/facture/{devisId}', name: 'app_facture_create', methods: 'POST')]
    public function create(string $devisId, Request $request): JsonResponse
    {
        try {
            return $this->json($this->factureService->create($devisId));
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }

    }

    #[Route('/facture/{id}', name: 'app_facture_read', methods: 'GET')]
    public function read(string $id, Request $request): JsonResponse
    {
        try {
            return $this->json($this->factureService->read($id));
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/facture/{id}', name: 'app_facture_update', methods: 'PUT')]
    public function update(string $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            return $this->json($this->factureService->update($id, $body));
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/facture/{id}/validate', name: 'app_facture_validate', methods: 'PUT')]
    public function validate(string $id): JsonResponse
    {
        try {
            return $this->json($this->factureService->changeState($id, "VALIDATE"));
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }
}
