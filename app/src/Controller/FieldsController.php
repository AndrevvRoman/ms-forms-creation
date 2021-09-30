<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function fields(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $fields = $em->getRepository(Field::class)->findAll();
        $normalizeService = new NormalizeService();
        return $normalizeService->normalizeFields($fields);
    }

    /**
     * @Route("/fields/add", name="fields_add")
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function fields_add(Request $request): Response
    {
        if (!$request->isMethod('post'))
        {
            return new Response(Response::HTTP_FORBIDDEN);
        }
        $isReqire = $request->request->get('isRequire');
        $title = $request->request->get('title');
        $placeHolder = $request->request->get('placeHolder');
        $inputType = $request->request->get('inputType');
        $responseType = $request->request->get('responseType');
        $parentId = $request->request->get('idForm');

        $newField = new Field();
        $newField->setIsRequire($isReqire)->setTitle($title)->setPlaceHolder($placeHolder)->setInputType($inputType)->setResponseType($responseType);
        $manager = $this->getDoctrine()->getManager();
        $parentForm = $manager->getRepository(Form::class)->find($parentId);
        $newField->setIdFormFK($parentForm);

        $manager->persist($newField);
        $manager->flush();

        return new Response(Response::HTTP_OK);
    }

    /**
     * @Route("/fields/remove", name="fields_remove")
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function remove_field(Request $request): Response
    {
        if (!$request->isMethod('post'))
        {
            return new Response(Response::HTTP_FORBIDDEN);
        }
        $id = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $field = $manager->getRepository(Field::class)->find($id);
        if ($field == null)
        {
            return new Response(Response::HTTP_NOT_FOUND);    
        }
        $manager->remove($field);
        $manager->flush();
        return new Response(Response::HTTP_OK);
    }

    /**
     * @Route("/fields/update", name="fields_update")
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function update_field(Request $request): Response
    {
        if (!$request->isMethod('post'))
        {
            return new Response(Response::HTTP_FORBIDDEN);
        }
        $id = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $field = $manager->getRepository(Field::class)->find($id);
        if ($field == null)
        {
            return new Response(Response::HTTP_NOT_FOUND);    
        }
        $isReqire = $request->request->get('isRequire');
        $title = $request->request->get('title');
        $placeHolder = $request->request->get('placeHolder');
        $inputType = $request->request->get('inputType');
        $responseType = $request->request->get('responseType');
        $parentId = $request->request->get('idForm');
        $manager = $this->getDoctrine()->getManager();
        $parentForm = $manager->getRepository(Form::class)->find($parentId);
        
        $field->setIsRequire($isReqire)->setTitle($title)->setPlaceHolder($placeHolder)->setInputType($inputType)->setResponseType($responseType);
        $field->setIdFormFK($parentForm);
        $manager->flush();

        return new Response(Response::HTTP_OK);
    }
}
