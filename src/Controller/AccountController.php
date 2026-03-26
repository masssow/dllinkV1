<?php

namespace App\Controller;

use App\Repository\QuranSessionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    #[Route('/mon-compte', name: 'app_account')]
    public function index(QuranSessionRepository $repo): Response
    {
        $user = $this->getUser();

        $sessions = $repo->findByOwnerWithAssignments($user);

        $dashboardStats = [
            'sessions' => count($sessions),
            'completedSessions' => 0,
            'hizbCompleted' => 0,
            'participants' => 0
        ];

        $sessionStats = [];


        foreach ($sessions as $session) {

            $assignments = $session->getQuranKhatmAssignments();

            $total = count($assignments);
            $completed = 0;

            foreach ($assignments as $a) {

                if ($a->isCompleted()) {
                    $completed++;
                    $dashboardStats['hizbCompleted']++;
                }

                if ($a->getParticipantName()) {
                    $dashboardStats['participants']++;
                }
            }

            $percent = $total > 0 ? round(($completed / $total) * 100) : 0;

            $sessionStats[$session->getId()] = [
                'total' => $total,
                'completed' => $completed,
                'percent' => $percent
            ];

            if ($percent === 100) {
                $dashboardStats['completedSessions']++;
            }
        }

        return $this->render('pages/account/index.html.twig', [
            'sessions' => $sessions,
            'sessionStats' => $sessionStats,
            'dashboardStats' => $dashboardStats
        ]);
    }
}
