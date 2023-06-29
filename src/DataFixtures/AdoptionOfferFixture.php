<?php

namespace App\DataFixtures;

use App\Entity\AdoptionOffer;
use App\Entity\Message;
use App\Repository\AdoptantRepository;
use App\Repository\AnnonceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AdoptionOfferFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        protected AdoptantRepository $adoptantRepository,
        protected AnnonceRepository $annonceRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        require_once 'vendor/autoload.php';
        $faker = Factory::create('fr_FR');
        // Fixture adoptionOffer
        $annonces = $this->annonceRepository->findAll();
        $adoptants = $this->adoptantRepository->findAll();

        foreach ($annonces as $annonce) {
            $dogs = $annonce->getDogs();
            $nbrOffer = mt_rand(0, 2);

            // pour pouvoir retenir nos adoptants
            $adoptantsIds = [];

            // AdoptionOffers
            for ($i = 0; $i < $nbrOffer; ++$i) {
                $adoptionOffer = new AdoptionOffer();
                $adoptionOffer->setAnnonce($annonce)
                    ->setCreatedAt(new \DateTimeImmutable());
                // message adoptionOffer
                $message = (new Message())
                    ->setContent($faker->paragraph())
                    ->setSubject($faker->sentence())
                    ->setIsFromAdoptant(true)
                    ->setCreatedAt(new \DateTimeImmutable());
                $adoptionOffer->addMessage($message);

                // on ne veux pas qu'un adoptant postule plusieur fois pour la meme annonce
                do {
                    $adoptantId = mt_rand(0, count($adoptants) - 1);
                } while (in_array($adoptantId, $adoptantsIds, true));

                $adoptantsIds[] = $adoptantId;

                $adoptant = $adoptants[$adoptantId];
                $adoptionOffer->setAdoptant($adoptant);

                // Dogs de l'adoptionOffer

                // nbr of dog dans la demande d'adoption
                $nbrDogsInOffer = mt_rand(0, count($dogs) - 1);

                // pour pouvoir retenir nos dogs
                $dogIds = [];

                for ($y = 0; $y < $nbrDogsInOffer; ++$y) {
                    // on ne veux pas ajoute le meme dog plusieurs fois pour la meme demande Adoption
                    do {
                        $dogId = mt_rand(0, count($dogs) - 1);
                    } while (in_array($dogId, $dogIds, true));
                    $dogIds[] = $dogId;

                    $dog = $dogs[$dogId];
                    $adoptionOffer->addDog($dog);
                }
                $manager->persist($adoptionOffer);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AnnnonceFixture::class,
            UserFixture::class,
        ];
    }
}
