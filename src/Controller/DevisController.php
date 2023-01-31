<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\CreateDevisEvent;
use App\Event\UpdateDevisEvent;
use App\Service\Core\IDevisService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/api')]
class DevisController extends AbstractController
{
    private readonly IDevisService $devisService;
    private readonly EventDispatcherInterface $dispatcher;
    public function __construct(IDevisService $devisService, EventDispatcherInterface $dispatcher)
    {
        $this->devisService =$devisService;
        $this->dispatcher = $dispatcher;
    }

    #[Route('/devis', name: 'app_devis', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json($this->devisService->getAll());
    }

    #[Route('/devis/commercial/{commercialId}', name: 'app_devis_commercial', methods: 'GET')]
    public function getByCommercial(string $commercialId): JsonResponse
    {
        try {
            return $this->json($this->devisService->getByCommercial($commercialId));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/devis/client/{clientId}', name: 'app_devis_client', methods: 'GET')]
    public function getByClient(string $clientId): JsonResponse
    {
        try {
            return $this->json($this->devisService->getByClient($clientId));
        }catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()],  $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/devis', name: 'app_devis_create', methods: 'POST', )]
    public function create(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $devis = $this->devisService->create($body);

            /** @var User $user */
            $user = $this->getUser();
            $event = new CreateDevisEvent($devis, $user);
            $this->dispatcher->dispatch($event, CreateDevisEvent::NAME);

            return $this->json($devis);
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }

    }

    #[Route('/devis/{id}', name: 'app_devis_read', methods: 'GET')]
    public function read(string $id): JsonResponse
    {
        try {
            return $this->json($this->devisService->read($id));
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/devis/{id}', name: 'app_devis_update', methods: 'PUT')]
    public function update(string $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $devis = $this->devisService->update($id, $body);

            /** @var User $user */
            $user = $this->getUser();
            $event = new UpdateDevisEvent($devis, $user);
            $this->dispatcher->dispatch($event, UpdateDevisEvent::NAME);

            return $this->json($devis);
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }
}
