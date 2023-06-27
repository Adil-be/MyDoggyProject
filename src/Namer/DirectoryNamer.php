<?php

namespace App\Namer;

use Exception;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

class DirectoryNamer implements DirectoryNamerInterface
{
    /** 
     * @param \App\Entity\Image $object
     * @param \Vich\UploaderBundle\Mapping\PropertyMapping $mapping
     */
    public function directoryName($object, $mapping): string
    {

        $dog = $object->getDog();

        if (is_null($dog)) {
            throw new Exception("Your image isn't link to a dog!");
        }
        return (string) $dog->getId();
    }

}