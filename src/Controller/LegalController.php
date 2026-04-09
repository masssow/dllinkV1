<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class LegalController extends AbstractController
{
    #[Route('/conditions-d-utilisation', name: 'app_legal_cgu')]
    public function cgu(): Response
    {
        return $this->render('legal/cgu.html.twig');
    }

    #[Route('/cookies', name: 'app_legal_cookies')]
    public function cookies(): Response
    {
        return $this->render('legal/cookie.html.twig');
    }

    #[Route('/politique-de-confidentialite', name: 'app_legal_privacy')]
    public function privacy(): Response
    {
        return $this->render('legal/privacy.html.twig');
    }

    #[Route('/mentions-legales', name: 'app_legal_mentions')]
    public function mentions(): Response
    {
        return $this->render('legal/mentions.html.twig');
    }
}
