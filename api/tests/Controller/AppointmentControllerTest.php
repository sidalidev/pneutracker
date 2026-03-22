<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppointmentControllerTest extends WebTestCase
{
    /**
     * Test que la liste des rendez-vous fonctionne.
     * Ce test PASSE correctement.
     */
    public function testListAppointments(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/appointments');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }

    /**
     * Test la creation d'un rendez-vous.
     *
     * BUG PLANTE : le test attend un code 200 (OK) alors que le controlleur
     * retourne correctement un 201 (Created). Le test echoue donc toujours.
     */
    public function testCreateAppointment(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/appointments', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'clientName' => 'Jean Dupont',
            'clientPhone' => '06 12 34 56 78',
            'vehiclePlate' => 'AB-123-CD',
            'tireReference' => 'Michelin Primacy 4 205/55R16',
            'quantity' => 4,
            'scheduledAt' => '2026-04-15 10:00',
            'franchise' => 'Nantes Centre',
        ]));

        // BUG : devrait etre assertResponseStatusCodeSame(201) car le controlleur
        // retourne Response::HTTP_CREATED (201), pas 200
        $this->assertResponseStatusCodeSame(200);

        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Jean Dupont', $data['clientName']);
        $this->assertEquals('pending', $data['status']);
    }

    /**
     * Test qu'un telephone invalide est rejete.
     *
     * BUG PLANTE : ce test s'attend a ce que le controlleur valide le format
     * du telephone et retourne une erreur 400. Mais le controlleur n'a aucune
     * validation de format telephone — il accepte tout, donc le rendez-vous
     * est cree avec succes (201) au lieu d'etre rejete (400).
     */
    public function testCreateAppointmentInvalidPhone(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/appointments', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'clientName' => 'Marie Martin',
            'clientPhone' => 'pas-un-telephone',
            'vehiclePlate' => 'EF-456-GH',
            'tireReference' => 'Continental PremiumContact 6 195/65R15',
            'quantity' => 2,
            'scheduledAt' => '2026-04-16 14:30',
            'franchise' => 'Rennes Nord',
        ]));

        // BUG : le controlleur ne valide pas le format telephone,
        // donc il retourne 201 au lieu de 400
        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }
}
