<?php

namespace App\Namer;

// VichUploader ne permet pas de nommer le dossier via ID
// error phpstan fix in new relase
// https://stackoverflow.com/questions/75397287/phpstan-class-implements-generic-interface-but-does-not-specify-its-types-error

use App\Entity\Image;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @param Image $object
     *
     * @throws \Exception
     *
     * @phpstan-ignore-next-line
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        if (!($object instanceof Image)) {
            throw new \InvalidArgumentException('The $object parameter must be an instance of Image.');
        }

        $dog = $object->getDog();

        if (null === $dog) {
            throw new \Exception("Your image isn't linked to a dog!");
        }

        return $dog->getId().'/';
    }
}
