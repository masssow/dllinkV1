<?php

namespace App\Service;

use App\Entity\QuranKhatmAssignment;
use App\Entity\QuranSession;
use App\Repository\QuranSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SessionCreatorService
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerInterface $slugger,
        private QuranSessionRepository $sessionRepository
    ) {}

    public function prepare(QuranSession $session): QuranSession
    {
        $now = new \DateTimeImmutable();

        // Champs communs
        if (!$session->getCreatedAt()) {
            $session->setCreatedAt($now);
        }

        if (!$session->getStatus()) {
            $session->setStatus('active');
        }

        // Date de fin par défaut si absente
        if ($session->getScheduledAt()) {
            $session->setScheduledAt(
                $session->getScheduledAt()->setTime(0, 0, 0)
            );
        }

        if ($session->getExpiresAt()) {
            $session->setExpiresAt(
                $session->getExpiresAt()->setTime(23, 59, 59)
            );
        } else {
            $session->setExpiresAt(
                $now->modify('+30 days')->setTime(23, 59, 59)
            );
        }
        

        // Génération slug unique
        if (!$session->getSlug()) {
            $session->setSlug($this->generateUniqueSlug($session->getTitle()));
        }

        // Logique métier selon type
        match ($session->getType()) {
            'khatm' => $this->prepareKhatm($session),
            'dua'   => $this->prepareDua($session),
            default => throw new \InvalidArgumentException(sprintf(
                'Type de session non supporté : %s',
                $session->getType()
            )),
        };

        return $session;
    }

    private function prepareKhatm(QuranSession $session): void
    {
        // Khatm = 30 hizb fixes
        $session->setTotalTarget(30);

        // Sécurité : éviter doublons si on rappelle prepare()
        if ($session->getQuranKhatmAssignments()->count() > 0) {
            return;
        }

        for ($i = 1; $i <= 30; $i++) {
            $assignment = new QuranKhatmAssignment();
            $assignment->setQuranSession($session);
            $assignment->setJuzNumber($i);

            $session->addQuranKhatmAssignment($assignment);
            $this->em->persist($assignment);
        }
    }

    private function prepareDua(QuranSession $session): void
    {
        // Sécurité minimale : dua doit avoir un objectif
        if (!$session->getTotalTarget() || $session->getTotalTarget() <= 0) {
            throw new \InvalidArgumentException(
                'Une session de type dua doit avoir un objectif de répétitions valide.'
            );
        }

        // Sécurité minimale : label principal recommandé
        if (!$session->getDuaLabel()) {
            $session->setDuaLabel('Invocation collective');
        }

        // Aucune génération de hizb ici
    }

    private function generateUniqueSlug(?string $title): string
    {
        $baseSlug = $this->slugger->slug($title ?: 'session')->lower()->toString();

        if ($baseSlug === '') {
            $baseSlug = 'session-' . bin2hex(random_bytes(4));
        }

        $slug = $baseSlug;
        $i = 1;

        while ($this->sessionRepository->findOneBy(['slug' => $slug])) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }

        return $slug;
    }
}
