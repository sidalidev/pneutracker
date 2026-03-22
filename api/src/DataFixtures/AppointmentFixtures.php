<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Appointment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppointmentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fixtures = [
            [
                'clientName' => 'Jean Dupont',
                'clientPhone' => '06 12 34 56 78',
                'vehiclePlate' => 'AB-123-CD',
                'tireReference' => 'Michelin Primacy 4 205/55R16',
                'quantity' => 4,
                'scheduledAt' => '+2 hours',
                'status' => 'pending',
                'franchise' => 'Nantes Centre',
                'notes' => 'Client fidele, 3eme visite',
            ],
            [
                'clientName' => 'Marie Martin',
                'clientPhone' => '06 98 76 54 32',
                'vehiclePlate' => 'EF-456-GH',
                'tireReference' => 'Continental PremiumContact 6 195/65R15',
                'quantity' => 2,
                'scheduledAt' => '+4 hours',
                'status' => 'confirmed',
                'franchise' => 'Nantes Centre',
                'notes' => null,
            ],
            [
                'clientName' => 'Pierre Leroy',
                'clientPhone' => '07 11 22 33 44',
                'vehiclePlate' => 'IJ-789-KL',
                'tireReference' => 'Bridgestone Turanza T005 225/45R17',
                'quantity' => 4,
                'scheduledAt' => '-1 hour',
                'status' => 'completed',
                'franchise' => 'Rennes Nord',
                'notes' => 'Equilibrage inclus',
            ],
            [
                'clientName' => 'Sophie Bernard',
                'clientPhone' => '06 55 44 33 22',
                'vehiclePlate' => 'MN-012-OP',
                'tireReference' => 'Goodyear EfficientGrip 185/60R15',
                'quantity' => 4,
                'scheduledAt' => '+1 day',
                'status' => 'pending',
                'franchise' => 'Brest Ocean',
                'notes' => null,
            ],
            [
                'clientName' => 'Luc Moreau',
                'clientPhone' => '07 66 77 88 99',
                'vehiclePlate' => 'QR-345-ST',
                'tireReference' => 'Pirelli Cinturato P7 205/60R16',
                'quantity' => 2,
                'scheduledAt' => '-2 days',
                'status' => 'cancelled',
                'franchise' => 'Nantes Centre',
                'notes' => 'Annule par le client — reporté',
            ],
            [
                'clientName' => 'Isabelle Roux',
                'clientPhone' => '06 33 22 11 00',
                'vehiclePlate' => 'UV-678-WX',
                'tireReference' => 'Hankook Ventus Prime 3 215/55R17',
                'quantity' => 4,
                'scheduledAt' => '+3 hours',
                'status' => 'confirmed',
                'franchise' => 'Rennes Nord',
                'notes' => 'Vehicule de societe',
            ],
            [
                'clientName' => 'Thomas Girard',
                'clientPhone' => '07 44 55 66 77',
                'vehiclePlate' => 'YZ-901-AB',
                'tireReference' => 'Dunlop Sport Maxx RT2 225/40R18',
                'quantity' => 2,
                'scheduledAt' => '+5 hours',
                'status' => 'pending',
                'franchise' => 'Brest Ocean',
                'notes' => 'Pneus sportifs, verifier compatibilite',
            ],
            [
                'clientName' => 'Nathalie Petit',
                'clientPhone' => '06 88 99 00 11',
                'vehiclePlate' => 'CD-234-EF',
                'tireReference' => 'Michelin CrossClimate 2 195/55R16',
                'quantity' => 4,
                'scheduledAt' => '+2 days',
                'status' => 'pending',
                'franchise' => 'Rennes Nord',
                'notes' => 'Passage aux 4 saisons',
            ],
            [
                'clientName' => 'Francois Dubois',
                'clientPhone' => '07 22 33 44 55',
                'vehiclePlate' => 'GH-567-IJ',
                'tireReference' => 'Continental WinterContact TS 870 205/55R16',
                'quantity' => 4,
                'scheduledAt' => '-3 hours',
                'status' => 'completed',
                'franchise' => 'Brest Ocean',
                'notes' => null,
            ],
            [
                'clientName' => 'Claire Laurent',
                'clientPhone' => '06 77 66 55 44',
                'vehiclePlate' => 'KL-890-MN',
                'tireReference' => 'Vredestein Quatrac Pro 215/60R16',
                'quantity' => 4,
                'scheduledAt' => '-1 day',
                'status' => 'cancelled',
                'franchise' => 'Rennes Nord',
                'notes' => 'Stock indisponible, a replanifier',
            ],
        ];

        foreach ($fixtures as $data) {
            $appointment = new Appointment();
            $appointment->setClientName($data['clientName']);
            $appointment->setClientPhone($data['clientPhone']);
            $appointment->setVehiclePlate($data['vehiclePlate']);
            $appointment->setTireReference($data['tireReference']);
            $appointment->setQuantity($data['quantity']);
            $appointment->setScheduledAt(new \DateTime($data['scheduledAt']));
            $appointment->setStatus($data['status']);
            $appointment->setFranchise($data['franchise']);
            $appointment->setNotes($data['notes']);

            $manager->persist($appointment);
        }

        $manager->flush();
    }
}
