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
        $destination = __DIR__.'/../../public/images/dogs/';

        // we delete the content of the $destination before uploading the images
        $succes = $this->deleteDir($destination);

        foreach ($dogs as $dog) {
            // $namefolder = $dog->getId();
            $numberImage = mt_rand(1, 4);
            $previousNum = [];
            for ($i = 1; $i <= $numberImage; ++$i) {
                $rndNum = mt_rand(0, 40);
                do {
                    $rndNum = mt_rand(0, 40);
                } while (in_array($rndNum, $previousNum, true));
                $imageFile = $this->createImage($rndNum);
                $previousNum[] = $rndNum;
                // $fileDestination = $destination.$namefolder;
                $fileDestination = $destination;
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

    // this function recursively delete the content of a folder
    // this is use to avoid creating the new images and their folder on top of the images of the previous fixture
    // this way we don't have to delete manuelly the content of /images/dogImages each type we load the fixtures
    // found on https://www.php.net/manual/en/function.rmdir.php#110489

    public function deleteDir(string $dir): bool
    {
        $files = [];
        if (false !== scandir($dir)) {
            $files = array_diff(scandir($dir), ['.', '..']);
        }

        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->deleteDir("$dir/$file") : unlink("$dir/$file");
        }

        // https://www.php.net/manual/en/function.rmdir.php
        return rmdir($dir);
    }
}
