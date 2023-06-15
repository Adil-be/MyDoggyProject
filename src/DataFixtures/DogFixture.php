<?php

namespace App\DataFixtures;

use App\Entity\Dog;
use App\Repository\AnnonceRepository;
use App\Repository\BreedRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class DogFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        protected BreedRepository $breedRepository,
        protected AnnonceRepository $annonceRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $annonces = $this->annonceRepository->findAll();
        // Fixture Dog
        $dogsNames =
            [
                'Admiral',
                'Alfred',
                'Asher',
                'Barrett',
                'Bond',
                'Buddy',
                'Churchill',
                'Cyrano',
                'Dilbert',
                'Duke',
                'Farley',
                'Frito',
                'Hawk',
                'Hunter',
                'Jupiter',
                'Kingston',
                'Lex',
                'Meatball',
                'Nolan',
                'Pedro',
                'Redmond',
                'Rosco',
                'Rufus',
                'Spud',
                'Twinkie',
                'Whiskey',
            ];
        $descrip = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quid enim possumus hoc agere divinius? Vitae autem degendae ratio maxime quidem illis placuit quieta. Sin dicit obscurari quaedam nec apparere, quia valde parva sint, nos quoque concedimus; Duo Reges: constructio interrete. Non dolere, inquam, istud quam vim habeat postea videro; Quid ergo attinet dicere';
        $antec = 'Nec enim, dum metuit, iustus est, et certe, si metuere destiterit, non erit; Hoc ille tuus non vult omnibusque ex rebus voluptatem quasi mercedem exigit.';

        $breeds = $this->breedRepository->findAll();
        foreach ($dogsNames as $dogName) {
            $dog = new Dog();
            $rnd = mt_rand(0, 1);
            $boolOption = [true, false];
            $rnd2 = mt_rand(0, 1);
            $dog
                ->setName($dogName)
                ->setDescription($descrip)
                ->setAntecedant($antec)
                ->setAcceptAnimmals($boolOption[$rnd])
                ->setIsLof($boolOption[$rnd2])
                ->setIsAdopted(false);

            $randnb = mt_rand(1, 3);
            for ($i = 0; $i <= $randnb; ++$i) {
                $randomNumber = mt_rand(0, count($breeds) - 1);
                $breed = $breeds[$randomNumber];
                $dog->addBreed($breed);
                $breeds[$randomNumber]->addDog($dog);
            }
            $randAnnonce = mt_rand(0, count($annonces) - 1);
            $dog->setAnnonce($annonces[$randAnnonce]);
            $manager->persist($dog);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            BreedFixture::class,
            AnnnonceFixture::class,
        ];
    }
}
