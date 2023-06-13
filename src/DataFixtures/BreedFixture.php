<?php

namespace App\DataFixtures;

use App\Entity\Breed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BreedFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $breedDataAll = json_decode(file_get_contents('https://dogapi.dog/api/v2/breeds'), true);
        $breedData = $breedDataAll['data'];
        for ($i = 0; $i < 10; ++$i) {
            $name = $breedData[$i]['attributes']['name'];
            $description = $breedData[$i]['attributes']['description'];
            $breed = new Breed();
            $breed
                ->setName($name)
                ->setDescritpion($description);
            $manager->persist($breed);
        }

        $manager->flush();
    }
}
