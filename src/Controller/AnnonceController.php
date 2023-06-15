<?php

namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    #[Route('/annonce{id}', name: 'annonce_detail')]
    public function detail(Annonce $annonce): Response
    {
        return $this->render('annonce/detail.html.twig', [
            'controller_name' => 'AnnonceController',
            'annonce' => $annonce,
        ]);
    }
}
