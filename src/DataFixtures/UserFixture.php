<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Adoptant;
use App\Entity\Annonceur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    private HttpClientInterface $client;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        HttpClientInterface $client
    ) {
        $this->hasher = $hasher;
        $this->client = $client;
    }

    public function load(ObjectManager $manager): void
    {
        require_once 'vendor/autoload.php';
        $faker = Factory::create('fr_FR');
        // fixture Admin

        $admin = new Admin();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@gmail.com')
            ->setCity('lyon')
            ->setPhoneNumber('+3312345678')
            ->setZipCode('69');
        $admin->setPassword(
            $this->hasher->hashPassword(
                $admin,
                '007'
            )
        );
        $manager->persist($admin);

        // Fixtures Adoptant
        for ($i = 0; $i < 5; ++$i) {
            $adoptant = new Adoptant();
            $adoptant
                ->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setCity($faker->city())
                ->setPhoneNumber($faker->phoneNumber())
                ->setZipCode($faker->postcode());
            $adoptant->setFirstName($faker->firstName())
                ->setLastName($faker->lastName());
            $adoptant->setPassword(
                $this->hasher->hashPassword(
                    $adoptant,
                    'mdp'
                )
            );
            $manager->persist($adoptant);
        }
        // fixture annonceur
        $assocData =
            [
                ['assocName' => 'SPA', 'email' => 'SPA@gmail.com'],
                ['assocName' => 'secondeChance', 'email' => 'secondeChance@gmail.com'],
                ['assocName' => 'ronRhone', 'email' => 'ronRhone@gmail.com'],
                ['assocName' => 'PAAW', 'email' => 'PAAW@gmail.com'],
                ['assocName' => 'PetAdoption', 'email' => 'PetAdoption@gmail.com'],
            ];
        foreach ($assocData as $assoc) {
            $annonceur = new Annonceur();
            $annonceur
                ->setUsername($faker->userName())
                ->setEmail($assoc['email'])
                ->setCity($faker->city())
                ->setPhoneNumber($faker->phoneNumber())
                ->setZipCode($faker->postcode());
            $annonceur->setName($assoc['assocName']);
            $annonceur->setPassword(
                $this->hasher->hashPassword(
                    $annonceur,
                    'mdp'
                )
            );
            $manager->persist($annonceur);
        }
        $manager->flush();
    }

    /**
     * @return array<string, mixed>
     */
    public function getUsers()
    {
        $response = $this->client->request(
            'GET',
            'https://jsonplaceholder.typicode.com/users'
        );

        return $response->toArray();
    }
}
