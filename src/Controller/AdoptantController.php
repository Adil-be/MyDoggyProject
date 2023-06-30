<?php

namespace App\Controller;

use App\Entity\Adoptant;
use App\Repository\AdoptionOfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADOPTANT')]
class AdoptantController extends AbstractController
{
    #[Route('/adoptant', name: 'app_adoptant')]
    public function index(AdoptionOfferRepository $adoptionOfferRepository): Response
    {
        /**
         * @var Adoptant $adoptant
         */
        $adoptant = $this->getUser();

        $adoptionOffers = $adoptionOfferRepository->findByAdoptantId($adoptant->getId());

        return $this->render('adoptant/index.html.twig', [
            'controller_name' => 'AdoptantController',
            'adoptionOffers' => $adoptionOffers,
        ]);
    }
}
