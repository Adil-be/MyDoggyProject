<?php

namespace App\DataFixtures;
use App\Entity\Annonce;
use App\Repository\AnnonceurRepository;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class AnnnonceFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(protected AnnonceurRepository $annonceurRepository)
    {

    }
    public function load(ObjectManager $manager): void
    {
        // Fixture Annonce
        $annonceurs = $this->annonceurRepository->findAll();
        for ($i = 1; $i < 5; ++$i) {
            $titleData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/posts/' . $i), true);
            $title = $titleData['title'];
            $randAnnonceur = mt_rand(0, count($annonceurs) - 1);
            $annonce = new Annonce();
            $date = new \DateTimeImmutable();
            $annonce
                ->setIsAvailable(true)
                ->setTitle($title)
                ->setAnnonceur($annonceurs[$randAnnonceur])
                ->setCreatedAt($date)
                ->setModifiedAt($date);
            $manager->persist($annonce);
        }

        

        // Fixture Images
        $manager->flush();

    }
    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];

    }

}