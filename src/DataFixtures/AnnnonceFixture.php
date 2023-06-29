<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Repository\AnnonceurRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnnnonceFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(protected AnnonceurRepository $annonceurRepository)
    {
    }
    public function load(ObjectManager $manager): void
    {
        require_once 'vendor/autoload.php';
        $faker = Factory::create('fr_FR');
        // Fixture Annonce
        $annonceurs = $this->annonceurRepository->findAll();
        for ($i = 0; $i < 10; $i++) {
            $randAnnonceur = mt_rand(0, count($annonceurs) - 1);
            $annonce = new Annonce();
            $date = new \DateTimeImmutable();
            $annonce
                ->setIsAvailable(true)
                ->setTitle($faker->sentence())
                ->setAnnonceur($annonceurs[$randAnnonceur])
                ->setCreatedAt($date)
                ->setModifiedAt($date);
            $manager->persist($annonce);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}