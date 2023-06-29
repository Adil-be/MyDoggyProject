<?php

namespace App\DataFixtures;

use App\Entity\Dog;
use App\Repository\AnnonceRepository;
use App\Repository\BreedRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class DogFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        protected BreedRepository $breedRepository,
        protected AnnonceRepository $annonceRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        require_once 'vendor/autoload.php';
        $faker = Factory::create('fr_FR');
        $annonces = $this->annonceRepository->findAll();
        // Fixture Dog
        $breeds = $this->breedRepository->findAll();
        $max = count($annonces) * 3;
        for ($i = 0; $i < $max; ++$i) {
            $dog = new Dog();
            $dog
                ->setName($faker->firstName())
                ->setDescription($faker->paragraph())
                ->setAntecedant($faker->sentence())
                ->setAcceptAnimmals($faker->boolean())
                ->setIsLof($faker->boolean());

            $randnb = mt_rand(1, 2);
            for ($x = 0; $x <= $randnb; ++$x) {
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
