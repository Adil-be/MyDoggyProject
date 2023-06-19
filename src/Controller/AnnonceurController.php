<?php

namespace App\Controller;

use App\Entity\Annonce;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ANNONCEUR')]
class AnnonceurController extends AbstractController
{
    #[Route('/annonceur', name: 'app_annonceur')]
    public function index(): Response
    {
        $annonceur = $this->getUser();

        return $this->render('annonceur/index.html.twig', [
            'controller_name' => 'AnnonceurController',
            'annonceur' => $annonceur,
        ]);
    }

    #[Route('/annonce/{id}/update', name: 'annonce_update')]
    #[Route('/annonce/new', name: 'annonce_new')]
    public function annonceUpdate(): Response
    {
        $annonceur = $this->getUser();

        return $this->render('annonceur/index.html.twig', [
            'controller_name' => 'AnnonceurController',
            'annonceur' => $annonceur,
        ]);
    }

    #[Route('/annonce/{id}/adoptionOffer', name: 'annonce_adoption_offers')]
    public function annonceAdoptionOffers(Annonce $annonce): Response
    {
        return $this->render('defaut/index.html.twig', [
            'controller_name' => 'AnnonceurController',
            'annonce' => $annonce,
        ]);
    }
}
