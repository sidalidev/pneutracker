<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Appointment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Appointment>
 */
class AppointmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appointment::class);
    }

    /**
     * Trouve tous les rendez-vous d'une franchise.
     *
     * @return Appointment[]
     */
    public function findByFranchise(string $franchise): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.franchise = :franchise')
            ->setParameter('franchise', $franchise)
            ->orderBy('a.scheduledAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les rendez-vous a venir (scheduledAt > maintenant, pas annules).
     *
     * @return Appointment[]
     */
    public function findUpcoming(): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.scheduledAt > :now')
            ->andWhere('a.status != :cancelled')
            ->setParameter('now', new \DateTime())
            ->setParameter('cancelled', Appointment::STATUS_CANCELLED)
            ->orderBy('a.scheduledAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les rendez-vous par statut.
     *
     * BUG PLANTE : le total inclut les rendez-vous annules,
     * ce qui fausse les statistiques du dashboard.
     *
     * @return array{pending: int, confirmed: int, completed: int, cancelled: int, total: int}
     */
    public function countByStatus(): array
    {
        $qb = $this->createQueryBuilder('a')
            ->select('a.status, COUNT(a.id) as count')
            ->groupBy('a.status');

        $results = $qb->getQuery()->getResult();

        $counts = [
            'pending' => 0,
            'confirmed' => 0,
            'completed' => 0,
            'cancelled' => 0,
            'total' => 0,
        ];

        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['count'];
            // BUG : on additionne TOUS les statuts, y compris cancelled
            $counts['total'] += (int) $row['count'];
        }

        return $counts;
    }

    /**
     * Trouve les rendez-vous d'aujourd'hui.
     *
     * @return Appointment[]
     */
    public function findToday(): array
    {
        $startOfDay = new \DateTime('today');
        $endOfDay = new \DateTime('tomorrow');

        return $this->createQueryBuilder('a')
            ->andWhere('a.scheduledAt >= :start')
            ->andWhere('a.scheduledAt < :end')
            ->setParameter('start', $startOfDay)
            ->setParameter('end', $endOfDay)
            ->orderBy('a.scheduledAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
