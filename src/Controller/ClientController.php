<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\Client\CreateClientEvent;
use App\Event\Client\UpdateClientEvent;
use App\Service\Core\IClientService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/api')]
class ClientController extends AbstractController
{
    private readonly IClientService $clientService;
    private readonly EventDispatcherInterface $dispatcher;
    public function __construct(IClientService $clientService, EventDispatcherInterface $dispatcher)
    {
        $this->clientService = $clientService;
        $this->dispatcher = $dispatcher;
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
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/client/{prospectId}', name: 'app_client_create', methods: 'POST')]
    public function createFromProspect(string $prospectId, Request $request): JsonResponse
    {
        try {
            $client = $this->clientService->create($prospectId);

            /** @var User $user */
            $user = $this->getUser();
            $event = new CreateClientEvent($client, $user);
            $this->dispatcher->dispatch($event, CreateClientEvent::NAME);

            return $this->json($client->jsonSerialize());
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/client/{id}', name: 'app_client_details', methods: 'GET')]
    public function getClientDetails(string $id): JsonResponse
    {
        try {
            return $this->json($this->clientService->read($id)->jsonSerializeDetails());
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/client/{id}', name: 'app_client_update', methods: 'PUT')]
    public function updateClient(string $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $client = $this->clientService->update($id, $body);

            /** @var User $user */
            $user = $this->getUser();
            $event = new UpdateClientEvent($client, $user);
            $this->dispatcher->dispatch($event, UpdateClientEvent::NAME);

            return $this->json($client->jsonSerializeDetails());
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()],$e->getCode() != 0 ? $e->getCode() : 400);
        }
    }
}
