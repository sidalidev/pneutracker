<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AppointmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AppointmentRepository::class)]
#[ORM\Table(name: 'appointment')]
#[ORM\HasLifecycleCallbacks]
class Appointment
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const VALID_STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_COMPLETED,
        self::STATUS_CANCELLED,
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $clientName;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $clientPhone;

    #[ORM\Column(type: Types::STRING, length: 15)]
    private string $vehiclePlate;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $tireReference;

    #[ORM\Column(type: Types::INTEGER)]
    private int $quantity;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $scheduledAt;

    #[ORM\Column(type: Types::STRING, length: 20)]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $franchise;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    // --- Getters & Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClientName(): string
    {
        return $this->clientName;
    }

    public function setClientName(string $clientName): self
    {
        $this->clientName = $clientName;
        return $this;
    }

    public function getClientPhone(): string
    {
        return $this->clientPhone;
    }

    public function setClientPhone(string $clientPhone): self
    {
        $this->clientPhone = $clientPhone;
        return $this;
    }

    public function getVehiclePlate(): string
    {
        return $this->vehiclePlate;
    }

    public function setVehiclePlate(string $vehiclePlate): self
    {
        $this->vehiclePlate = $vehiclePlate;
        return $this;
    }

    public function getTireReference(): string
    {
        return $this->tireReference;
    }

    public function setTireReference(string $tireReference): self
    {
        $this->tireReference = $tireReference;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getScheduledAt(): \DateTimeInterface
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(\DateTimeInterface $scheduledAt): self
    {
        $this->scheduledAt = $scheduledAt;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, self::VALID_STATUSES, true)) {
            throw new \InvalidArgumentException(
                sprintf('Statut invalide "%s". Valeurs acceptees : %s', $status, implode(', ', self::VALID_STATUSES))
            );
        }
        $this->status = $status;
        return $this;
    }

    public function getFranchise(): string
    {
        return $this->franchise;
    }

    public function setFranchise(string $franchise): self
    {
        $this->franchise = $franchise;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * Serialise le rendez-vous en tableau pour l'API JSON.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'clientName' => $this->clientName,
            'clientPhone' => $this->clientPhone,
            'vehiclePlate' => $this->vehiclePlate,
            'tireReference' => $this->tireReference,
            'quantity' => $this->quantity,
            'scheduledAt' => $this->scheduledAt->format('Y-m-d H:i'),
            'status' => $this->status,
            'franchise' => $this->franchise,
            'notes' => $this->notes,
            'createdAt' => $this->createdAt->format('Y-m-d H:i'),
        ];
    }
}
