<?php

namespace App\Controller;

use App\Entity\QuranDuaContribution;
use App\Entity\QuranKhatmAssignment;
use App\Entity\QuranSession;
use App\Form\QuranSessionFormType;
use App\Repository\QuranKhatmAssignmentRepository;
use App\Repository\QuranSessionRepository;
use App\Service\SessionCreatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


final class QuranSessionController extends AbstractController
{

    #[Route('/session/new', name: 'app_quran_session_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        SessionCreatorService $sessionCreatorService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $session = new QuranSession();
        $session->setOwner($this->getUser());

        $form = $this->createForm(QuranSessionFormType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // Validation métier spécifique
            if ($session->getType() === 'dua' && !$session->getExpiresAt()) {
                $form->get('expiresAt')->addError(
                    new \Symfony\Component\Form\FormError(
                        'Veuillez renseigner une date de clôture pour une session de type Du‘a.'
                    )
                );
            }

            if ($session->getScheduledAt() && $session->getExpiresAt()) {
                if ($session->getExpiresAt() < $session->getScheduledAt()) {
                    $form->get('expiresAt')->addError(
                        new \Symfony\Component\Form\FormError(
                            'La date de clôture doit être postérieure ou égale à la date de début.'
                        )
                    );
                }
            }

            // Si tout est bon, on crée réellement la session
            if ($form->isValid()) {
                $sessionCreatorService->prepare($session);

                $em->persist($session);
                $em->flush();

                $shareUrl = $this->generateUrl(
                    'app_quran_session_public_show',
                    ['slug' => $session->getSlug()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );

                $this->addFlash(
                    'success',
                    'Session créée avec succès. Lien de partage : ' . $shareUrl
                );

                return $this->redirectToRoute('app_quran_session_public_show', [
                    'slug' => $session->getSlug()
                ]);
            }
        }

        return $this->render('quran_session/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/s/{slug}/participate', name: 'app_quran_session_participate', methods: ['POST'])] 

    public function participate(
        string $slug,
        Request $request,
        QuranSessionRepository $sessionRepo,
        QuranKhatmAssignmentRepository $assignmentRepo,
        EntityManagerInterface $em
    ): RedirectResponse {

        $session = $sessionRepo->findOneBy(['slug' => $slug]);

        if (!$session) {
            throw $this->createNotFoundException();
        }

        $participant = trim($request->request->get('participant'));
        $hizb = (int) $request->request->get('hizb');
        // dd($participant, $hizb);
        if (!$participant || !$hizb) {
            $this->addFlash('error', 'Veuillez saisir votre nom et choisir un hizb.');
            return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $slug]);
        }

        $assignment = $assignmentRepo->findOneBy([
            'quranSession' => $session,
            'juzNumber' => $hizb
        ]);
        // dd($assignment);
        if (!$assignment) {
            $this->addFlash('error', 'Hizb introuvable.');
            return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $slug]);
        }

        if ($assignment->getParticipantName()) {
            $this->addFlash('error', 'Ce hizb est déjà attribué.');
            return $this->redirectToRoute(
                'app_quran_session_public_show',
                ['slug' => $slug]
            );
        }


        $assignment->setParticipantName($participant);

        $em->flush();

        $this->addFlash('success', 'Participation enregistrée.');

        return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $slug]);
    }


    #[Route('/api/session/{slug}/state', name: 'app_session_state')]
    public function sessionState(
        string $slug,
        QuranSessionRepository $sessionRepo,
        QuranKhatmAssignmentRepository $assignmentRepo
    ): Response {

        $session = $sessionRepo->findOneBy(['slug' => $slug]);

        if (!$session) {
            return $this->json(['error' => 'not found'], 404);
        }

        $assignments = $assignmentRepo->findBy(
            ['quranSession' => $session],
            ['juzNumber' => 'ASC']
        );

        $data = [];

        foreach ($assignments as $a) {
            $data[] = [
                'id' => $a->getId(),
                'juz' => $a->getJuzNumber(),
                'participant' => $a->getParticipantName(),
                'completed' => $a->isCompleted()
            ];
        }

        return $this->json($data);
    }


    #[Route('/s/{slug}', name: 'app_quran_session_public_show')]
    public function publicShow(
        QuranSessionRepository $sessionRepo,
        string $slug, QuranKhatmAssignmentRepository $assignmentRepo, Request $request
    ): Response {

        $session = $sessionRepo->findOneBy(['slug' => $slug]);

        if (!$session) {
            throw $this->createNotFoundException('Session introuvable.');
        }

        // récupérer les hizbs
        $viewData = [
            'session' => $session,
            'assignments' => [],
            'completedCount' => 0,
            'duaContributions' => [],
            'duaTotalDone' => 0,
            'duaPercent' => 0,
            'isGoalReached' => false,
        ];

        //Khatm : récupérer les assignments et calculer le nombre de hizbs complétés

        if ($session->getType() === 'khatm') {
            $assignments = $assignmentRepo->findBy(
                ['quranSession' => $session],
                ['juzNumber' => 'ASC']
            );

            $completedCount = $assignmentRepo->count([
                'quranSession' => $session,
                'isCompleted' => true
            ]);

            $viewData['assignments'] = $assignments;
            $viewData['completedCount'] = $completedCount;
            $viewData['isGoalReached'] = $completedCount >= ($session->getTotalTarget() ?? 30);
        }


        // Du‘a : récupérer les contributions, calculer le total et le pourcentage d’avancement
        if ($session->getType() === 'dua') {
            $duaContributions = $session->getQuranDuaContributions()->toArray();

            usort($duaContributions, function ($a, $b) {
                return $b->getCreatedAt() <=> $a->getCreatedAt();
            });

            $duaTotalDone = array_reduce($duaContributions, function ($carry, $contribution) {
                return $carry + ($contribution->getContributionCount() ?? 0);
            }, 0);

            $target = $session->getTotalTarget() ?? 0;
            $duaPercent = $target > 0 ? min(100, round(($duaTotalDone / $target) * 100)) : 0;

            $viewData['duaContributions'] = $duaContributions;
            $viewData['duaTotalDone'] = $duaTotalDone;
            $viewData['duaPercent'] = $duaPercent;
            $viewData['isGoalReached'] = $target > 0 && $duaTotalDone >= $target;
        }

        $shareUrl = $request->getSchemeAndHttpHost() . $this->generateUrl('app_quran_session_public_show', [
            'slug' => $session->getSlug(),
        ]);

        if ($session->getType() === 'khatm') {
            $shareText = "📖 Participez à ce kaamil sur DahiraLink.\n\n"
                . "Choisissez votre hizb et rejoignez la lecture :\n"
                . $shareUrl . "\n\n"
                . "Qu’Allah accepte cette action collective. 🤲";
        } elseif ($session->getType() === 'dua') {
            $shareText = "🤲 Rejoignez cette pratique (Barkélou) collective sur DahiraLink.\n\n"
                . "Ajoutez votre participation :\n"
                . $shareUrl . "\n\n"
                . "Qu’Allah accepte nos invocations. Âmîne.";
        } else {
            $shareText = "Participez à cette session sur DahiraLink :\n\n" . $shareUrl;
        }

        $whatsappShareUrl = 'https://wa.me/?text=' . urlencode($shareText);
        $telegramShareUrl = 'https://t.me/share/url?url=' . urlencode($shareUrl) . '&text=' . urlencode($shareText);

        $ogImage = $session->getImageName()
            ? $request->getSchemeAndHttpHost() . '/uploads/sessionsCovers/' . $session->getImageName()
            : $request->getSchemeAndHttpHost() . '/images/og-default-dahiralink.png';

        $viewData['shareUrl'] = $shareUrl;
        $viewData['shareText'] = $shareText;
        $viewData['whatsappShareUrl'] = $whatsappShareUrl;
        $viewData['telegramShareUrl'] = $telegramShareUrl;
        $viewData['ogImage'] = $ogImage;

        return $this->render('quran_session/public_show.html.twig', $viewData);
    }

    #[Route('/s/{slug}/participate-dua', name: 'app_quran_session_participate_dua', methods: ['POST'])]
    public function participateDua(
        string $slug,
        Request $request,
        QuranSessionRepository $sessionRepo,
        EntityManagerInterface $em
    ): RedirectResponse {

        $session = $sessionRepo->findOneBy(['slug' => $slug]);

        if (!$session) {
            throw $this->createNotFoundException();
        }

        // sécurité métier
        if ($session->getType() !== 'dua') {
            $this->addFlash('error', 'Cette session ne permet pas ce type de participation.');
            return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $slug]);
        }

        $participant = trim($request->request->get('participant'));
        $count = (int) $request->request->get('count');

        if (!$participant || $count <= 0) {
            $this->addFlash('error', 'Veuillez renseigner votre nom et un nombre valide.');
            return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $slug]);
        }

        $contribution = new QuranDuaContribution();
        $contribution->setParticipantName($participant);
        $contribution->setContributionCount($count);
        $contribution->setQuranSession($session);
        $contribution->setCreatedAt(new \DateTimeImmutable());

        $em->persist($contribution);
        $em->flush();

        $this->addFlash('success', 'Votre participation a été enregistrée. Qu’Allah accepte.');

        return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $slug]);
    }


    #[Route('/s/{slug}/toggle/{id}', name: 'app_hizb_toggle')]
    public function toggle(
        QuranKhatmAssignment $assignment,
        EntityManagerInterface $em
    ) {

        if (!$assignment->getParticipantName()) {
            return $this->redirectToRoute(
                'app_quran_session_public_show',
                ['slug' => $assignment->getQuranSession()->getSlug()]
            );
        }

        $assignment->setIsCompleted(
            !$assignment->isCompleted()
        );

        $em->flush();

        return $this->redirectToRoute(
            'app_quran_session_public_show',
            ['slug' => $assignment->getQuranSession()->getSlug()]
        );
    }


    #[Route('/api/hizb/toggle/{id}', name: 'app_hizb_toggle_api', methods: ['POST'])]
    public function toggleAjax(
        QuranKhatmAssignment $assignment,
        EntityManagerInterface $em,
        QuranKhatmAssignmentRepository $repo
    ) {

        if (!$assignment->getParticipantName()) {
            return $this->json(['error' => 'not assigned'], 400);
        }

        $assignment->setIsCompleted(!$assignment->isCompleted());

        $em->flush();

        $session = $assignment->getQuranSession();

        $completedCount = $repo->count([
            'quranSession' => $session,
            'isCompleted' => true
        ]);

        return $this->json([
            'completed' => $assignment->isCompleted(),
            'completedCount' => $completedCount,
            'total' => $session->getTotalTarget(),
            'juz' => $assignment->getJuzNumber()
        ]);
    }

}
