<?php

namespace App\Controller;

use App\Service\Core\IProspectService;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ProspectController extends AbstractController
{
    private readonly IProspectService $prospectService;
    public function __construct(IProspectService $prospectService)
    {
        $this->prospectService = $prospectService;
    }

    #[Route('/prospect', name: 'app_prospect_list', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json($this->prospectService->getAll());
    }

    #[Route('/prospect/commercial/{commercialId}', name: 'app_prospect_list_commercial', methods: 'GET')]
    public function getByCommercial(string $commercialId): JsonResponse
    {
        try {
            return $this->json($this->prospectService->getByCommercial($commercialId));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/prospect/commercial/{commercialId}', name: 'app_prospect_create', methods: 'POST')]
    public function createProspect(string $commercialId, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $prospect = $this->prospectService->create($commercialId, $body);
            return $this->json($prospect->jsonSerializeProspect());
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/prospect/{id}', name: 'app_prospect_details', methods: 'GET')]
    public function getById(string $id): JsonResponse
    {
        try {
            return $this->json($this->prospectService->read($id)->jsonSerializeProspect());
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/prospect/{id}', name: 'app_prospect_update', methods: 'PUT')]
    public function update(string $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $prospect = $this->prospectService->update($id, $body);
            return $this->json($prospect->jsonSerializeProspect());
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }
}
