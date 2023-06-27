<?php

// VichUploader ne permet pas de nommer le dossier via ID



namespace App\Namer;

use App\Entity\Image;
use Exception;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class DirectoryNamer implements DirectoryNamerInterface
{
    /** 
     * @param Image $object
     * @param PropertyMapping $mapping
     */
    public function directoryName($object, PropertyMapping $mapping): string
    {

        $dog = $object->getDog();

        if (is_null($dog)) {
            throw new Exception("Your image isn't link to a dog!");
        }
        dd($dog);
        return (string) $dog->getId() . '/';
    }

}