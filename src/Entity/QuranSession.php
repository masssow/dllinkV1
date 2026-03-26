<?php

namespace App\Entity;

use App\Repository\QuranSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuranSessionRepository::class)]
class QuranSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255,  unique: true)]
    private ?string $slug = null;

    #[ORM\Column(nullable: true)]
    private ?int $totalTarget = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $duaLabel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expiresAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

   
    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    /**
     * @var Collection<int, QuranKhatmAssignment>
     */
    #[ORM\OneToMany(targetEntity: QuranKhatmAssignment::class, mappedBy: 'quranSession')]
    private Collection $quranKhatmAssignments;

    /**
     * @var Collection<int, QuranDuaContribution>
     */
    #[ORM\OneToMany(targetEntity: QuranDuaContribution::class, mappedBy: 'quranSession')]
    private Collection $quranDuaContributions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $scheduledAt = null;

    public function __construct()
    {
    
        $this->quranKhatmAssignments = new ArrayCollection();
        $this->quranDuaContributions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getTotalTarget(): ?int
    {
        return $this->totalTarget;
    }

    public function setTotalTarget(?int $totalTarget): static
    {
        $this->totalTarget = $totalTarget;

        return $this;
    }

    public function getDuaLabel(): ?string
    {
        return $this->duaLabel;
    }

    public function setDuaLabel(?string $duaLabel): static
    {
        $this->duaLabel = $duaLabel;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt): static
    {
        $this->expiresAt = $expiresAt;

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

  
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }


    /**
     * @return Collection<int, QuranKhatmAssignment>
     */
    public function getQuranKhatmAssignments(): Collection
    {
        return $this->quranKhatmAssignments;
    }

    public function addQuranKhatmAssignment(QuranKhatmAssignment $quranKhatmAssignment): static
    {
        if (!$this->quranKhatmAssignments->contains($quranKhatmAssignment)) {
            $this->quranKhatmAssignments->add($quranKhatmAssignment);
            $quranKhatmAssignment->setQuranSession($this);
        }

        return $this;
    }

    public function removeQuranKhatmAssignment(QuranKhatmAssignment $quranKhatmAssignment): static
    {
        if ($this->quranKhatmAssignments->removeElement($quranKhatmAssignment)) {
            // set the owning side to null (unless already changed)
            if ($quranKhatmAssignment->getQuranSession() === $this) {
                $quranKhatmAssignment->setQuranSession(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuranDuaContribution>
     */
    public function getQuranDuaContributions(): Collection
    {
        return $this->quranDuaContributions;
    }

    public function addQuranDuaContribution(QuranDuaContribution $quranDuaContribution): static
    {
        if (!$this->quranDuaContributions->contains($quranDuaContribution)) {
            $this->quranDuaContributions->add($quranDuaContribution);
            $quranDuaContribution->setQuranSession($this);
        }

        return $this;
    }

    public function removeQuranDuaContribution(QuranDuaContribution $quranDuaContribution): static
    {
        if ($this->quranDuaContributions->removeElement($quranDuaContribution)) {
            // set the owning side to null (unless already changed)
            if ($quranDuaContribution->getQuranSession() === $this) {
                $quranDuaContribution->setQuranSession(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getScheduledAt(): ?\DateTimeImmutable
    {
        return $this->scheduledAt;
    }

    public function setScheduledAt(?\DateTimeImmutable $scheduledAt): static
    {
        $this->scheduledAt = $scheduledAt;

        return $this;
    }
}
