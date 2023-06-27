<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Annonceur;
use App\Entity\Dog;
use App\Repository\DogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ANNONCEUR')]
class AnnonceurController extends AbstractController
{
    #[Route('/annonceur', name: 'app_annonceur')]
    public function index(Request $request, DogRepository $dogRepository, int $idDog = null): Response
    {
        /** @var Annonceur $annonceur */
        $annonceur = $this->getUser();
        if (null != $idDog) {
            $dog = $dogRepository->findWithAnnonceurId($annonceur->getId(), $idDog);
            $dog->setIsAdopted(true);
            $dogRepository->save($dog, true);
        }

        return $this->render('annonceur/index.html.twig', [
            'controller_name' => 'AnnonceurController',
            'annonceur' => $annonceur,
        ]);
    }

    #[Route('/adopted', name: 'app_annonceur_adopted')]
    public function dogAdopted(
        Request $request,
        DogRepository $dogRepository,
        Dog $dog
    ): Response {
        /** @var Annonceur $annonceur */
        $annonceur = $this->getUser();
        if ($dog->getAnnonce()->getAnnonceur() == $annonceur) {
            $dog->setIsAdopted(true);
            $dogRepository->save($dog, true);

            $this->addFlash('success', 'Dog has been Change as adopted !');

            return $this->redirectToRoute('app_annonceur');
        } else {
            throw $this->createAccessDeniedException("You don't have access!");
        }
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
