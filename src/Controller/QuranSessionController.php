<?php

namespace App\Controller;

use App\Entity\QuranKhatmAssignment;
use App\Entity\QuranSession;
use App\Form\QuranSessionFormType;
use App\Repository\QuranKhatmAssignmentRepository;
use App\Repository\QuranSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;


final class QuranSessionController extends AbstractController
{
    #[Route('/session/new', name: 'app_quran_session_new')]
    public function new(Request $request, EntityManagerInterface $em, Security $security, SluggerInterface $slugger, QuranSessionRepository $sessionRepo): Response
    {
        
        $this->denyAccessUnlessGranted('ROLE_USER');

        $session = new QuranSession();
        $session->setOwner($this->getUser());

        $form = $this->createForm(QuranSessionFormType::class, $session);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 1. Timestamps
            $now = new \DateTimeImmutable();
            $session->setCreatedAt($now);
            $session->setExpiresAt($now->modify('+30 days'));
            
            // définir total hizb
            $session->setTotalTarget(30);

            for ($i = 1; $i <= $session->getTotalTarget(); $i++) {

                $assignment = new QuranKhatmAssignment();

                $assignment->setQuranSession($session);
                $assignment->setJuzNumber($i);

                $em->persist($assignment);
            }

            // 2. Slug auto
            $baseSlug = $slugger->slug($session->getTitle())->lower()->toString();
            if ($baseSlug === '') {
                $baseSlug = 'session-' . bin2hex(random_bytes(4));
            }
            
            $slug = $baseSlug;
            $i = 1;

            while ($sessionRepo->findOneBy(['slug' => $slug])) {
                $slug = $baseSlug . '-' . $i;
                $i++;
            }

            $session->setSlug($slug);

            // 3. création de liste d Hizb pour les sessions de type Khatm
           
            $em->persist($session);
            $em->flush();

            // 4. Lien partageable (optionnel pour flash ou redirection)
            $shareUrl = $this->generateUrl(
                'app_quran_session_public_show',
                ['slug' => $session->getSlug()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $this->addFlash('success', 'Session créée avec succès. Lien de partage : ' . $shareUrl);

            return $this->redirectToRoute('app_quran_session_public_show', ['slug' => $session->getSlug()]); // Redirige vers l’espace personnel ou la liste des sessions
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
        string $slug, QuranKhatmAssignmentRepository $assignmentRepo
    ): Response {

        $session = $sessionRepo->findOneBy(['slug' => $slug]);

        if (!$session) {
            throw $this->createNotFoundException('Session introuvable.');
        }

        // récupérer les hizbs
        $assignments = $assignmentRepo->findBy(
            ['quranSession' => $session],
            ['juzNumber' => 'ASC']
        );

      
        $completedCount = $assignmentRepo->count([
            'quranSession' => $session,
            'isCompleted' => true
        ]);

        return $this->render('quran_session/public_show.html.twig', [
            'session' => $session,
            'assignments' => $assignments,
            'completedCount' => $completedCount
        ]);
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
