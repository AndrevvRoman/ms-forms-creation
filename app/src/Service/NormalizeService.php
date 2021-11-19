<?php
namespace App\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NormalizeService
{

    public function normalizeByGroup($object, $groups = ['groups' => 'main']) {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $serializer = new Serializer([new ObjectNormalizer($classMetadataFactory)]);

        return $serializer->normalize($object, null, [
            'groups' => $groups['groups'],
        ]);
    }
}