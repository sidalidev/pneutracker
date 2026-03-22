<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly AppointmentRepository $repository,
    ) {
    }

    /**
     * Statistiques du dashboard.
     *
     * BUG PLANTE : le total du jour inclut les rendez-vous annules.
     * On utilise findToday() qui retourne TOUS les statuts,
     * puis on compte sans filtrer les cancelled.
     */
    #[Route('/api/dashboard', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $todayAppointments = $this->repository->findToday();
        $statusCounts = $this->repository->countByStatus();

        // Regrouper les RDV du jour par franchise
        $byFranchise = [];
        foreach ($todayAppointments as $appointment) {
            $franchise = $appointment->getFranchise();
            if (!isset($byFranchise[$franchise])) {
                $byFranchise[$franchise] = 0;
            }
            // BUG : on compte TOUS les rendez-vous, y compris les annules
            $byFranchise[$franchise]++;
        }

        return $this->json([
            // BUG : count() inclut les cancelled dans le total du jour
            'todayTotal' => count($todayAppointments),
            'todayByFranchise' => $byFranchise,
            'statusCounts' => $statusCounts,
            'upcoming' => count($this->repository->findUpcoming()),
        ]);
    }
}
