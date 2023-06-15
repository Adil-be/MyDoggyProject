<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use App\Repository\AnnonceurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(
        AnnonceRepository $annonceRepository,
        AnnonceurRepository $annonceurRepository
    ): Response {
        $annonces = $annonceRepository->findBy(
            [
                'isAvailable' => true,
            ],
            [
                'modifiedAt' => 'DESC',
            ],
            5,
            0
        );
        $annonceurs = $annonceurRepository->findByRecentAnnonce();
        dump($annonceurs);

        dump($annonces);

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'annonces' => $annonces,
            'annonceurs' => $annonceurs,
        ]);
    }

    #[Route(path: '/redirectCompte', name: 'security_redirect_account')]
    public function redirectCompte(): RedirectResponse
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles, true)) {
            return $this->redirectToRoute('dashboard_admin');
        } elseif (in_array('ROLE_ANNONCEUR', $roles, true)) {
            return $this->redirectToRoute('app_annonceur');
        } elseif (in_array('ROLE_ADOPTANT', $roles, true)) {
            return $this->redirectToRoute('app_adoptant');
        } else {
            return $this->redirectToRoute('app_default');
        }
    }
}
