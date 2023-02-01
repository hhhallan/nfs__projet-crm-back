<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\Prospect\CreateProspectEvent;
use App\Event\Prospect\UpdateProspectEvent;
use App\Service\Core\IProspectService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/api')]
class ProspectController extends AbstractController
{
    private readonly IProspectService $prospectService;
    private readonly EventDispatcherInterface $dispatcher;
    public function __construct(IProspectService $prospectService, EventDispatcherInterface $dispatcher)
    {
        $this->prospectService = $prospectService;
        $this->dispatcher = $dispatcher;
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
    public function create(string $commercialId, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $prospect = $this->prospectService->create($commercialId, $body);

            /** @var User $user */
            $user = $this->getUser();
            $event = new CreateProspectEvent($prospect, $user);
            $this->dispatcher->dispatch($event, CreateProspectEvent::NAME);

            return $this->json($prospect->jsonSerialize());
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/prospect/{id}', name: 'app_prospect_details', methods: 'GET')]
    public function getById(string $id): JsonResponse
    {
        try {
            return $this->json($this->prospectService->read($id)->jsonSerializeDetails());
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

            /** @var User $user */
            $user = $this->getUser();
            $event = new UpdateProspectEvent($prospect, $user);
            $this->dispatcher->dispatch($event, UpdateProspectEvent::NAME);

            return $this->json($prospect->jsonSerializeDetails());
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }
}
