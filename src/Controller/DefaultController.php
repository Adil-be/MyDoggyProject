<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
    #[Route(path: '/redirectCompte', name: 'security_redirect_account')]
    public function redirectCompte(): RedirectResponse
    {
        $user = $this->getUser();
        $roles = $user->getRoles();
        if (in_array('ROLE_ADMIN', $roles)) {
            return $this->redirectToRoute('app_default');
        } else if (in_array('ROLE_ANNONCEUR', $roles)) {
            return $this->redirectToRoute('app_annonceur');
        } else if (in_array('ROLE_ADOPTANT', $roles)) {
            return $this->redirectToRoute('app_adoptant');
        } else {
            return $this->redirectToRoute('app_default');
        }

    }

}