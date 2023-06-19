<?php

namespace App\EventListener;

use App\Entity\Annonce;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AdminAnnonceUpdateSuscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        // Notez qu'aucune priorité n'est définie (c'est le cas le plus courant, pour moi).
        // Ceci est équivalent à une priorité de 0
        // Notez également que l'on utilise le FQCN des événement, et non une constante. Les deux fonctionnent ;)
        return [
            BeforeEntityPersistedEvent::class => ['createAnnonceDatetime'],
            BeforeEntityUpdatedEvent::class => ['updateAnnonceDatetime'],
        ];
    }

    /**
     * @param BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event
     */
    public function updateAnnonceDatetime($event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Annonce)) {
            return;
        }

        $dateTime = new \DateTimeImmutable();
        // On définit le nouveau mot de passe, en hashant la propriété plainPassword (temporaire)
        $entity->setModifiedAt($dateTime);
    }

    /**
     * @param BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent $event
     */
    public function createAnnonceDatetime($event): void
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Annonce)) {
            return;
        }

        $dateTime = new \DateTimeImmutable();
        // On définit le nouveau mot de passe, en hashant la propriété plainPassword (temporaire)
        $entity->setCreatedAt($dateTime);
        $entity->setModifiedAt($dateTime);
    }
}
