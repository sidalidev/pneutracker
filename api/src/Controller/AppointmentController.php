<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/appointments')]
class AppointmentController extends AbstractController
{
    public function __construct(
        private readonly AppointmentRepository $repository,
        private readonly EntityManagerInterface $em,
    ) {
    }

    /**
     * Liste tous les rendez-vous, avec filtre optionnel par franchise.
     */
    #[Route('', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $franchise = $request->query->get('franchise');

        $appointments = $franchise
            ? $this->repository->findByFranchise($franchise)
            : $this->repository->findAll();

        $data = array_map(fn(Appointment $a) => $a->toArray(), $appointments);

        return $this->json($data);
    }

    /**
     * Affiche un rendez-vous par son ID.
     */
    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $appointment = $this->repository->find($id);

        if (!$appointment) {
            return $this->json(['error' => 'Rendez-vous introuvable'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($appointment->toArray());
    }

    /**
     * Cree un nouveau rendez-vous.
     *
     * BUG PLANTE : pas de validation du format telephone.
     * Si clientPhone contient des caracteres invalides, ca passe quand meme
     * et ca peut crasher en aval (envoi de SMS, par exemple).
     */
    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Validation basique des champs obligatoires
        $required = ['clientName', 'clientPhone', 'vehiclePlate', 'tireReference', 'quantity', 'scheduledAt', 'franchise'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->json(
                    ['error' => sprintf('Le champ "%s" est obligatoire', $field)],
                    Response::HTTP_BAD_REQUEST
                );
            }
        }

        // BUG : pas de validation du format telephone !
        // Un numero comme "abc123" ou "!@#$" sera accepte sans erreur.

        $appointment = new Appointment();
        $appointment->setClientName($data['clientName']);
        $appointment->setClientPhone($data['clientPhone']);
        $appointment->setVehiclePlate($data['vehiclePlate']);
        $appointment->setTireReference($data['tireReference']);
        $appointment->setQuantity((int) $data['quantity']);
        $appointment->setScheduledAt(new \DateTime($data['scheduledAt']));
        $appointment->setFranchise($data['franchise']);

        if (isset($data['notes'])) {
            $appointment->setNotes($data['notes']);
        }

        $this->em->persist($appointment);
        $this->em->flush();

        return $this->json($appointment->toArray(), Response::HTTP_CREATED);
    }

    /**
     * Met a jour le statut d'un rendez-vous.
     */
    #[Route('/{id}/status', methods: ['PATCH'])]
    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $appointment = $this->repository->find($id);

        if (!$appointment) {
            return $this->json(['error' => 'Rendez-vous introuvable'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['status'])) {
            return $this->json(['error' => 'Le champ "status" est obligatoire'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $appointment->setStatus($data['status']);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $this->em->flush();

        return $this->json($appointment->toArray());
    }
}
