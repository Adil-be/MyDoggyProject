<?php

// VichUploader ne permet pas de nommer le dossier via ID

namespace App\Namer;

use App\Entity\Image;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class DirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @param Image $object
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {
        $dog = $object->getDog();

        if (is_null($dog)) {
            throw new \Exception("Your image isn't link to a dog!");
        }
        dd($dog);

        return (string) $dog->getId().'/';
    }
}
