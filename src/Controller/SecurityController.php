<?php

namespace App\Controller;

use App\Entity\Adoptant;
use App\Form\AdoptantType;
use App\Repository\AdoptantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'security_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/inscritpion', name: 'security_sign_in')]
    public function signIn(Request $request, AdoptantRepository $adoptantRepository): Response
    {
        $adoptant = new Adoptant();

        $form = $this->createForm(AdoptantType::class, $adoptant, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $adoptantRepository->save($adoptant, true);

            $this->addFlash(
                'success',
                'Your Account has been created'
            );

            return $this->redirectToRoute('app_default');
        }

        return $this->render('security/signIn.html.twig', [
            'controller_name' => 'AnnonceurController',
            'form' => $form->createView()
        ]);

    }
}