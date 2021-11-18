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

/**
 * @Route("/api/v1")
 */
class FieldsController extends AbstractController
{
    /**
     * @Route("/fields/all", name="fields", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function fields(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $fields = $em->getRepository(Field::class)->findAll();
        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($fields),
            'count' => count($fields),
            'message' => 'All fields',
        ]);
    }

    /**
     * @Route("/fields/find", name="field", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function field(Request $r): Response
    {
        $em = $this->getDoctrine()->getManager();
        $data = json_decode($r->getContent(), true);
        $id = $data['id'];
        $field = $em->getRepository(Field::class)->find($id);
        if ($field == null) {
            return $this->json([
                'data' =>  [],
                'count' => 0,
                'message' => 'Field not founded'
            ]);
        }
        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($field),
            'count' => 1,
            'message' => 'Field founded'
        ]);
    }

    /**
     * @Route("/fields/add", name="field_add", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function field_add(Request $r): Response
    {
        $data = json_decode($r->getContent(), true);
        $isReqire = $data['isRequire'];
        $isActive = $data['isActive'];
        $title = $data['title'];
        $placeHolder = $data['placeHolder'];
        $inputType = $data['inputType'];
        $parentId = $data['idForm'];
        $possbleValues = $data['possbleValues'] ?? array();

        $newField = new Field();
        $newField->setIsRequire($isReqire)->setTitle($title)->setPlaceHolder($placeHolder);
        $newField->setInputType($inputType)->setIsActive($isActive)->setPossbleValues($possbleValues);
        $manager = $this->getDoctrine()->getManager();

        $parentForm = $manager->getRepository(Form::class)->find($parentId);
        if ($parentForm == null) {
            return $this->json([
                'data' =>  [],
                'count' => 0,
                'message' => 'Parent from not founded'
            ]);
        }
        $newField->setIdFormFK($parentForm);

        $manager->persist($newField);
        $manager->flush();

        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($newField),
            'count' => 1,
            'message' => 'Field created'
        ]);
    }

    /**
     * @Route("/fields/remove", name="fields_remove", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function remove_field(Request $r): Response
    {
        $data = json_decode($r->getContent(), true);
        $id = $data['id'];

        $manager = $this->getDoctrine()->getManager();
        $field = $manager->getRepository(Field::class)->find($id);
        if ($field == null) {
            return $this->json([
                'data' => [],
                'message' => 'Field not found',
                'count' => 0
            ]);
        }

        $manager->remove($field);
        $manager->flush();

        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($field),
            'count' => 1,
            'message' => 'Field deleted'
        ]);
    }

    /**
     * @Route("/fields/update", name="fields_update", methods={"PATCH"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function update_field(Request $r): Response
    {
        $data = json_decode($r->getContent(), true);
        $id = $data['id'];

        $manager = $this->getDoctrine()->getManager();
        $field = $manager->getRepository(Field::class)->find($id);
        if ($field == null) {
            return $this->json([
                'data' =>  [],
                'count' => 0,
                'message' => 'Field not founded'
            ]);
        }

        $isReqire = $data['isRequire'];
        $isActive = $data['isActive'];
        $title = $data['title'];
        $placeHolder = $data['placeHolder'];
        $inputType = $data['inputType'];
        $parentId = $data['idForm'];
        $possbleValues = $data['possbleValues'];

        $manager = $this->getDoctrine()->getManager();
        $parentForm = $manager->getRepository(Form::class)->find($parentId);
        if ($parentForm == null) {
            return $this->json([
                'data' =>  [],
                'count' => 0,
                'message' => 'Parent from not founded'
            ]);
        }
        $field->setIsRequire($isReqire)->setTitle($title)->setPlaceHolder($placeHolder)->setInputType($inputType);
        $field->setIdFormFK($parentForm)->setIsActive($isActive)->setPossbleValues($possbleValues);
        $manager->flush();

        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($field),
            'count' => 1,
            'message' => 'Field updated'
        ]);
    }
}
