<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\Facture\CreateFactureEvent;
use App\Event\Facture\UpdateFactureEvent;
use App\Event\Facture\ValidateFactureEvent;
use App\Service\Core\IFactureService;
use App\Service\Core\IHistoryService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[Route('/api')]
class FactureController extends AbstractController
{
    private readonly IFactureService $factureService;
    private readonly IHistoryService $historyService;
    private readonly EventDispatcherInterface $dispatcher;
    public function __construct(IFactureService $factureService, IHistoryService $historyService, EventDispatcherInterface $dispatcher)
    {
        $this->factureService = $factureService;
        $this->historyService = $historyService;
        $this->dispatcher = $dispatcher;
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
            $facture = $this->factureService->create($devisId);

            /** @var User $user */
            $user = $this->getUser();
            $event = new CreateFactureEvent($facture, $user);
            $this->dispatcher->dispatch($event, CreateFactureEvent::NAME);

            return $this->json($facture->jsonSerialize());
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }

    }

    #[Route('/facture/{id}', name: 'app_facture_read', methods: 'GET')]
    public function read(string $id, Request $request): JsonResponse
    {
        try {
            $facture = $this->factureService->read($id);

            $json = $facture->jsonSerialize();
            $json['history'] = array_map(function ($history) {
                return $history->jsonFromTarget();
            }, $this->historyService->getByTargetId($facture->getId()));
            return $this->json($json);
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/facture/{id}', name: 'app_facture_update', methods: 'PUT')]
    public function update(string $id, Request $request): JsonResponse
    {
        $body = json_decode($request->getContent(), true);
        try {
            $facture = $this->factureService->update($id, $body);

            /** @var User $user */
            $user = $this->getUser();
            $event = new UpdateFactureEvent($facture, $user);
            $this->dispatcher->dispatch($event, UpdateFactureEvent::NAME);

            $json = $facture->jsonSerialize();
            $json['history'] = array_map(function ($history) {
                return $history->jsonFromTarget();
            }, $this->historyService->getByTargetId($facture->getId()));
            return $this->json($json);
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }

    #[Route('/facture/{id}/validate', name: 'app_facture_validate', methods: 'PUT')]
    public function validate(string $id): JsonResponse
    {
        try {
            $facture = $this->factureService->changeState($id, "VALIDATE");

            /** @var User $user */
            $user = $this->getUser();
            $event = new ValidateFactureEvent($facture, $user);
            $this->dispatcher->dispatch($event, ValidateFactureEvent::NAME);

            $json = $facture->jsonSerialize();
            $json['history'] = array_map(function ($history) {
                return $history->jsonFromTarget();
            }, $this->historyService->getByTargetId($facture->getId()));
            return $this->json($json);
        } catch (Exception $e) {
            return $this->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode() != 0 ? $e->getCode() : 400);
        }
    }
}
