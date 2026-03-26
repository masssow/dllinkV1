<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalicyController extends AbstractController
{
    #[Route('/legalicy', name: 'app_legalicy')]
    public function index(): Response
    {
        return $this->render('legalicy/index.html.twig', [
            'controller_name' => 'LegalicyController',
        ]);
    }
}
