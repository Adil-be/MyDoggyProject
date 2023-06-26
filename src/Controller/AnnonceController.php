<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Annonceur;
use App\Entity\Dog;
use App\Entity\Image;
use App\Form\AnnonceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AnnonceController extends AbstractController
{
    #[Route('/annonce/{id}/detail', name: 'annonce_detail')]
    public function detail(Annonce $annonce): Response
    {
        return $this->render('annonce/detail.html.twig', [
            'controller_name' => 'AnnonceController',
            'annonce' => $annonce,
        ]);
    }

    #[IsGranted('ROLE_ANNONCEUR')]
    #[Route('/annonce/{id}/update', name: 'annonce_update')]
    #[Route('/annonce/new', name: 'annonce_new')]

    public function annonceUpdate(Request $request, ?Annonce $annonce): Response
    {

        /** @var Annonceur $annonceur */
        $annonceur = $this->getUser();

        if (is_null($annonce)) {
            $isCreation = true;
            $image = new Image;
            $dog = (new Dog)->addImage($image);

            $annonce = (new Annonce())
                ->setAnnonceur($annonceur)
                ->addDog($dog);
            ;
        }

        $form = $this->createForm(AnnonceType::class, $annonce, [
            'isCreation' => $isCreation
        ]);

        $form->handleRequest($request);

        return $this->render('annonce/annonceUpdate.html.twig', [
            'controller_name' => 'AnnonceurController',
            'annonceur' => $annonceur,
            'form' => $form->createView()
        ]);
    }
}