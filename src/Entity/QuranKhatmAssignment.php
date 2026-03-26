<?php

namespace App\Entity;

use App\Repository\QuranKhatmAssignmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuranKhatmAssignmentRepository::class)]
class QuranKhatmAssignment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $juzNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $participantName = null;

    #[ORM\Column]
    private ?bool $isCompleted = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'quranKhatmAssignments')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?QuranSession $quranSession = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJuzNumber(): ?int
    {
        return $this->juzNumber;
    }

    public function setJuzNumber(?int $juzNumber): static
    {
        $this->juzNumber = $juzNumber;

        return $this;
    }

    public function getParticipantName(): ?string
    {
        return $this->participantName;
    }

    public function setParticipantName(?string $participantName): static
    {
        $this->participantName = $participantName;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): static
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getQuranSession(): ?QuranSession
    {
        return $this->quranSession;
    }

    public function setQuranSession(?QuranSession $quranSession): static
    {
        $this->quranSession = $quranSession;

        return $this;
    }
}
