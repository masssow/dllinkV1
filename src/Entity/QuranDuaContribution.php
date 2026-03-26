<?php

namespace App\Entity;

use App\Repository\QuranDuaContributionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuranDuaContributionRepository::class)]
class QuranDuaContribution
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $participantName = null;

    #[ORM\Column]
    private ?int $contributionCount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'quranDuaContributions')]
    private ?QuranSession $quranSession = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getcontributionCount(): ?int
    {
        return $this->contributionCount;
    }

    public function setcontributionCount(int $contributionCount): static
    {
        $this->contributionCount = $contributionCount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
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
