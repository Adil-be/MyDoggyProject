<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Adoptant;
use App\Entity\Annonceur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
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
        $usersData = json_decode(file_get_contents('https://jsonplaceholder.typicode.com/users'), true);

        for ($i = 0; $i < 5; ++$i) {
            $userData = $usersData[$i];
            $adoptant = new Adoptant();

            $fullname = explode(' ', $userData['name']);
            $firstName = $fullname[0];
            $lastName = $fullname[1];
            $adoptant
                ->setUsername($userData['username'])
                ->setEmail($userData['email'])
                ->setCity($userData['address']['city'])
                ->setPhoneNumber($userData['phone'])
                ->setZipCode($userData['address']['zipcode']);
            $adoptant->setFirstName($firstName)
                ->setLastName($lastName);
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
        for ($i = 5; $i < 10; ++$i) {
            $userData = $usersData[$i];
            $annonceur = new Annonceur();

            $annonceur
                ->setUsername($userData['username'])
                ->setEmail($assocData[$i - 5]['email'])
                ->setCity($userData['address']['city'])
                ->setPhoneNumber($userData['phone'])
                ->setZipCode($userData['address']['zipcode']);
            $annonceur->setName($assocData[$i - 5]['assocName']);
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
}
