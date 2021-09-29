<?php

namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\NormalizeService;
use App\Entity\Field;
use App\Entity\Form;

class FieldsController extends AbstractController
{
    /**
     * @Route("/fields", name="fields")
     * @return JsonResponse
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $fields = $em->getRepository(Field::class)->findAll();
        $normalizeService = new NormalizeService();
        return $normalizeService->normalizeFields($fields);
    }
    /**
     * @Route("/fields/add", name="fields_add")
     * @return JsonResponse
     */
    public function fields_add(Request $request): Response
    {
        $isReqire = $request->query->get('isRequire');
        $title = $request->query->get('title');
        $placeHolder = $request->query->get('placeHolder');
        $inputType = $request->query->get('inputType');
        $responseType = $request->query->get('responseType');
        $newField = new Field();
        $newField->setIsRequire($isReqire);
        $newField->setTitle($title);
        $newField->setPlaceHolder($placeHolder);
        $newField->setInputType($inputType);
        $newField->setResponseType($responseType);
        $manager = $this->getDoctrine()->getManager();
        $parentForm = $manager->getRepository(Form::class)->find($request->query->get('idForm'));
        $newField->setIdFormFK($parentForm);

        $manager->persist($newField);
        $manager->flush();
        return new Response(Response::HTTP_OK);
    }
}
