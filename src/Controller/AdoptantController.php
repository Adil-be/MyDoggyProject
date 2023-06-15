<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdoptantController extends AbstractController
{
    #[Route('/adoptant', name: 'app_adoptant')]
    public function index(): Response
    {
        return $this->render('adoptant/index.html.twig', [
            'controller_name' => 'AdoptantController',
        ]);
    }

    #[Route('/applyAdoption', name: 'adoptant_applyForAdoption')]
    public function applyAdoption(): Response
    {
        return $this->render('annonceur/index.html.twig', [
            'controller_name' => 'AnnonceurController',
        ]);
    }
}
