<?php

namespace App\DataFixtures;

use App\Entity\Breed;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BreedFixture extends Fixture
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function load(ObjectManager $manager): void
    {
        $breedDataAll = $this->getBreeds();
        // dd($breedDataAll);
        $breedData = $breedDataAll['data'];
        for ($i = 0; $i < 9; ++$i) {
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

    /**
     * @return array<string, mixed>
     */
    public function getBreeds()
    {
        $response = $this->client->request(
            'GET',
            'https://dogapi.dog/api/v2/breeds'
        );

        return $response->toArray();
    }
}
