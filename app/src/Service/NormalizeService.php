<?php

namespace App\Service;

use app\Entity\Field;
use app\Entity\ResponseType;
use app\Entity\InputType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class NormalizeService
{
    public function __construct()
    {
    }

    public function normalizeForms($forms)
    {
        $formsCollection = array();
        foreach ($forms as $form) 
        {
            $fields = $form->getFields();
            $fieldsCollection = array();
            foreach ($fields as $field) 
            {
                $fieldsCollection[] = array(
                    'id' => $field->getId(),
                    'isRequire' => $field->getIsRequire(),
                    'title' => $field->getTitle(),
                    'placeHolder' => $field->getPlaceHolder(),
                    'inputType' => $field->getInputType(),
                    'responseType' => $field->getResponseType(),
                    'idForm' => $field->getIdFormFK()->getId(),
                );
            }
            
            $formsCollection[] = array(
                'id' => $form->getId(),
                'name' => $form->getName(),
                'title' => $form->getTitle(),
                'userId' => $form->getUserId(),
                'fields' => $fieldsCollection,
            );
        }
        return new JsonResponse($formsCollection);
    }

    public function normalizeFields($fields)
    {
        $arrayCollection = array();
        foreach ($fields as $field) {
            $arrayCollection[] = array(
                'id' => $field->getId(),
                'isRequire' => $field->getIsRequire(),
                'title' => $field->getTitle(),
                'placeHolder' => $field->getPlaceHolder(),
                'inputType' => $field->getInputType(),
                'responseType' => $field->getResponseType(),
                'idForm' => $field->getIdFormFK()->getId(),
            );
        }
        return new JsonResponse($arrayCollection);
    }
}
