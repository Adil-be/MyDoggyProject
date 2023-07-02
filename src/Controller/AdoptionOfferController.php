<?php

namespace App\Controller;

use App\Entity\Adoptant;
use App\Entity\AdoptionOffer;
use App\Entity\Annonce;
use App\Entity\Message;
use App\Form\AdoptionOfferType;
use App\Form\MessageType;
use App\Repository\AdoptionOfferRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdoptionOfferController extends AbstractController
{
    #[Route('/adoption/offer', name: 'app_adoption_offer')]
    public function index(): Response
    {
        return $this->render('adoption_offer/index.html.twig', [
            'controller_name' => 'AdoptionOfferController',
        ]);
    }

    #[IsGranted('ROLE_ADOPTANT')]
    #[Route('/adoption/{id}/new', name: 'app_adoption_new')]
    public function applyForAdoption(Request $request, AdoptionOfferRepository $adoptionOfferRepository, Annonce $annonce): Response
    {
        /** @var Adoptant $user */
        $user = $this->getUser();

        if ($user->hasAlreadyApply($annonce)) {
            $this->addFlash('danger', 'You already have apply for that annonce please modify it instead of creating a new one');

            return $this->redirectToRoute('app_adoptant');
        }

        $message = (new Message())
            ->setIsFromAdoptant(true);
        $adoptionOffer = new AdoptionOffer();
        $adoptionOffer->addMessage($message);
        $adoptionOffer->setAnnonce($annonce);
        $adoptionOffer->setAdoptant($user);

        $form = $this->createForm(AdoptionOfferType::class, $adoptionOffer, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_adoption_new', [
                'id' => $adoptionOffer->getAnnonce()->getId(),
            ]),
            'idAnnonce' => $adoptionOffer->getAnnonce()->getId(),
            'isCreation' => true,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable();
            $message->setCreatedAt($date);
            $adoptionOffer->setCreatedAt($date);
            $adoptionOfferRepository->save($adoptionOffer, true);

            $this->addFlash('success', 'your Adoption offer has been created !');

            return $this->redirectToRoute('app_adoptant');
        }

        return $this->render('adoption_offer/index.html.twig', [
            'controller_name' => 'AnnonceurController',
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADOPTANT')]
    #[Route('/adoption/{id}/modify', name: 'app_adoption_modify')]
    public function modifyAdoption(
        Request $request,
        AdoptionOfferRepository $adoptionOfferRepository,
        AdoptionOffer $adoptionOffer,
        MessageRepository $messageRepository
    ): Response {
        /** @var Adoptant $user */
        $user = $this->getUser();
        if ($user != $adoptionOffer->getAdoptant()) {
            throw $this->createAccessDeniedException("You don't have access!");
        }

        // we don't want all the messages to be in the form only the first one
        $firstMessage = $messageRepository->findFirstMessageForm($adoptionOffer->getId());

        $form = $this->createForm(AdoptionOfferType::class, $adoptionOffer, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_adoption_modify', [
                'id' => $adoptionOffer->getId(),
            ]),
            'firstMessage' => $firstMessage,
            'idAnnonce' => $adoptionOffer->getAnnonce()->getId(),
            'isCreation' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adoptionOfferRepository->save($adoptionOffer, true);

            $this->addFlash('success', 'your Adoption offer has been modify !');

            return $this->redirectToRoute('app_adoptant');
        }

        return $this->render('adoption_offer/index.html.twig', [
            'controller_name' => 'AnnonceurController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/adoption/{id}/chat', name: 'app_adoption_chat')]
    public function adoptionChat(
        AdoptionOffer $adoptionOffer,
        Request $request,
        MessageRepository $messageRepository
    ): Response {
        if (
            $this->getUser() != $adoptionOffer->getAdoptant()
            && $this->getUser() != $adoptionOffer->getAnnonce()->getAnnonceur()
        ) {
            throw $this->createAccessDeniedException("You don't have access!");
        }

        $this->makeMessageViwed($adoptionOffer, $messageRepository);

        $canMessage = true;
        $form = null;
        $isFirstMessage = (count($adoptionOffer->getMessages()) <= 1);

        if ($isFirstMessage && ($this->getUser() instanceof Adoptant)) {
            $canMessage = false;
        }

        if ($canMessage) {
            $message = new Message();
            $message->setAdoptionOffer($adoptionOffer);
            $form = $this->createForm(MessageType::class, $message);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $message->setIsFromAdoptant($this->getUser() instanceof Adoptant);
                $message->setCreatedAt(new \DateTimeImmutable());

                $messageRepository->save($message, true);

                $this->addFlash('success', 'Message sent !');

                return $this->redirectToRoute('app_adoption_chat', ['id' => $adoptionOffer->getId()]);
            }
        }

        return $this->render('adoption_offer/adoptionChat.html.twig', [
            'canMessage' => $canMessage,
            'adoptionOffer' => $adoptionOffer,
            'form' => $form,
        ]);
    }

    public function makeMessageViwed(
        AdoptionOffer $adoptionOffer,
        MessageRepository $messageRepository
    ): void {
        $isAdoptant = $this->getUser() instanceof Adoptant;
        $messages = $adoptionOffer->getMessages()->filter(function ($m) use ($isAdoptant) {
            // return (true);
            return $m->isFromAdoptant() != $isAdoptant;
        });
        // dd($messages);

        foreach ($messages as $message) {
            $message->setViewed(true);
            $messageRepository->save($message, true);
        }
    }
}