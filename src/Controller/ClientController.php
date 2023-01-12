<?php

namespace App\Controller;

use App\Service\Core\IClientService;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ClientController extends AbstractController
{
    private readonly IClientService $clientService;
    public function __construct(IClientService $clientService)
    {
        $this->clientService = $clientService;
    }

    #[Route('/client', name: 'app_client_list', methods: 'GET')]
    public function list(): JsonResponse
    {
        return $this->json($this->clientService->getAll());
    }

    #[Route('/client/commercial/{commercialId}', name: 'app_client_list_commercial', methods: 'GET')]
    public function getByCommercial(string $commercialId): JsonResponse
    {
        try {
            return $this->json($this->clientService->getByCommercial($commercialId));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    #[Route('/client/{prospectId}', name: 'app_client_create', methods: 'POST')]
    public function createFromProspect(string $prospectId, Request $request): JsonResponse
    {
        // TODO : a faire
        throw new FeatureNotImplemented();
    }

    #[Route('/client/{id}', name: 'app_client_details', methods: 'GET')]
    public function getClientDetails(string $id): JsonResponse
    {
        // TODO : a faire
        throw new FeatureNotImplemented();
    }
}
