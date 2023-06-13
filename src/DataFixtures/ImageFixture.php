<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Repository\DogRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        protected DogRepository $dogRepository
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $dogs = $this->dogRepository->findAll();
        $fileSystem = new Filesystem();
        $destination = __DIR__.'/../../public/images/dogImages/';

        foreach ($dogs as $dog) {
            $namefolder = $dog->getId();
            $numberImage = mt_rand(1, 6);
            $previousNum = [];
            for ($i = 1; $i <= $numberImage; ++$i) {
                $rndNum = mt_rand(0, 40);
                while (in_array($rndNum, $previousNum, true)) {
                    $rndNum = mt_rand(0, 40);
                }
                $imageFile = $this->createImage($rndNum);
                $previousNum[] = $rndNum;
                $fileDestination = $destination.$namefolder;
                $fileSystem->copy(
                    $imageFile->getRealPath(),
                    $fileDestination.'/'.$imageFile->getFilename()
                );

                $image = new Image();
                $image
                    ->setSize($imageFile->getSize())
                    ->setAlt('image of a dog')
                    ->setPath($imageFile->getFilename())
                    ->setDog($dog);

                $manager->persist($image);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            DogFixture::class,
        ];
    }

    public function createImage(int $rndNum): UploadedFile
    {
        $folder = __DIR__.'/../../var/images/';
        $imageName = 'dog'.$rndNum.'.jpg';
        $src = $folder.$imageName;

        return new UploadedFile(
            path: $src,
            originalName: $imageName,
            mimeType: 'image/Jpeg',
            test: true
        );
    }
}
