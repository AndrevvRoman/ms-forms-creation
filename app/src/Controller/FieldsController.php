<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception;
use App\Service\NormalizeService;
use App\Entity\Field;
use App\Entity\Form;
use Exception as GlobalException;
use Psr\Log\LoggerInterface;

class FieldsController extends AbstractController
{
    /**
     * @Route("/fields", name="fields", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function fields(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $fields = $em->getRepository(Field::class)->findAll();
        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($fields)
        ]);
    }

    /**
     * @Route("/fields/add", name="fields_add", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function fields_add(Request $request): Response
    {
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
     * @Route("/fields/remove", name="fields_remove", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function remove_field(Request $request,LoggerInterface $logger): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data["id"];
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
     * @Route("/fields/update", name="fields_update", methods={"PATCH"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function update_field(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data["id"];
        $manager = $this->getDoctrine()->getManager();
        $field = $manager->getRepository(Field::class)->find($id);
        if ($field == null)
        {
            return new Response(Response::HTTP_NOT_FOUND);    
        }
        
        $isReqire = $data['isRequire'];
        $title = $data['title'];
        $placeHolder = $data['placeHolder'];
        $inputType = $data['inputType'];
        $responseType = $data['responseType'];
        $parentId = $data['idForm'];
        $manager = $this->getDoctrine()->getManager();
        $parentForm = $manager->getRepository(Form::class)->find($parentId);
        
        $field->setIsRequire($isReqire)->setTitle($title)->setPlaceHolder($placeHolder)->setInputType($inputType)->setResponseType($responseType);
        $field->setIdFormFK($parentForm);
        $manager->flush();

        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($field)
        ]);
    }
}
