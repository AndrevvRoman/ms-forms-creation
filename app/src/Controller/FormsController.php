<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Form;
use App\Message\FormAddMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\NormalizeService;


use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/api/v1", name="forms")
 */
class FormsController extends AbstractController
{
    /**
     * @Route("/forms/all", name="forms_all", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function forms(Request $r): Response
    {
        $data = json_decode($r->getContent(), true);
        $isFieldsReqire = $data['all'] ?? false;
        $em = $this->getDoctrine()->getManager();
        $forms = $em->getRepository(Form::class)->findAll();
        if (!$isFieldsReqire) {
            $data = (new NormalizeService())->normalizeByGroup($forms);
        } else {
            $data = (new NormalizeService())->normalizeByGroup($forms, ['groups' => ['main', 'additional']]);
        }
        return $this->json([
            'data' =>  $data,
            'message' => 'All forms',
            'count' => count($data)
        ]);
    }

    /**
     * @Route("/forms/find", name="form_find", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function form(Request $r): Response
    {
        $data = json_decode($r->getContent(), true);
        $isFieldsReqire = $data['all'] ?? false;
        $id = $data['id'];
        $em = $this->getDoctrine()->getManager();
        $form = $em->getRepository(Form::class)->find($id);
        if (!$isFieldsReqire) {
            $data = (new NormalizeService())->normalizeByGroup($form);
        } else {
            $data = (new NormalizeService())->normalizeByGroup($form, ['groups' => ['main', 'additional']]);
        }
        if ($form == null) {
            return $this->json([
                'data' =>  null,
                'count' => 0,
                'message' => 'Form not founded'
            ]);
        }
        return $this->json([
            'data' =>  $data,
            'count' => 1,
            'message' => 'Form founded'
        ]);
    }

    /**
     * @Route("/forms/find/fields", name="form_find_fields", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function find_form_fields(Request $r): Response
    {
        $data = json_decode($r->getContent(), true);
        $id = $data['id'];
        $em = $this->getDoctrine()->getManager();
        $form = $em->getRepository(Form::class)->find($id);
        if ($form == null) {
            return $this->json([
                'data' =>  null,
                'count' => 0,
                'message' => 'Form not founded'
            ]);
        }
        $fields = $form->getFields();
        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($fields),
            'count' => 1,
            'message' => 'Form founded'
        ]);
    }

    /**
     * @Route("/rabbit/forms/add", name="form_add_rabbit", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function add_forms_rabbit(Request $r, HubInterface $hub, MessageBusInterface $bus): Response
    {
        $data = json_decode($r->getContent(), true);
        $name = $data['name'];
        $title = $data['title'];
        $userId = $this->getUser()->getId();
        $bus->dispatch(new FormAddMessage($name, $title, $userId));

        return new Response('You form has been placed');
    }

    /**
     * @Route("/forms/add", name="form_add", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function add_forms(Request $r, HubInterface $hub): Response
    {
        $data = json_decode($r->getContent(), true);
        $name = $data['name'];
        $title = $data['title'];

        $userId = $this->getUser()->getId();

        $newForm = new Form();
        $newForm->setName($name)->setTitle($title)->setUserId($userId);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newForm);
        $manager->flush();

        $update = new Update(
            'subscribe_add_form',
            json_encode([
                'name' => $name,
                'title' => $title,
                'id' => $newForm->getId()
            ])
        );
        $hub->publish($update);

        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($newForm),
            'count' => 1,
            'message' => 'Form created'
        ]);
    }
    /**
     * @Route("/forms/remove", name="form_remove", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function remove_from(Request $request, HubInterface $hub): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $manager = $this->getDoctrine()->getManager();
        $form = $manager->getRepository(Form::class)->find($id);
        if ($form == null) {
            return $this->json([
                'data' => [],
                'message' => 'Form not found',
                'count' => 0
            ]);
        }

        $manager->remove($form);
        $manager->flush();

        $update = new Update(
            'subscribe_delete_form',
            json_encode([
                'id' => $id,
            ])
        );
        $hub->publish($update);

        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($form),
            'count' => 1,
            'message' => 'Form deleted'
        ]);
    }
    /**
     * @Route("/forms/update", name="form_update", methods={"PATCH"})
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return HttpResponse
     */
    public function update_from(Request $request, HubInterface $hub): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $manager = $this->getDoctrine()->getManager();
        $form = $manager->getRepository(Form::class)->find($id);
        if ($form == null) {
            return $this->json([
                'data' => [],
                'message' => 'Form not found',
                'count' => 0
            ]);
        }

        $name = $data['name'];
        $title = $data['title'];

        $userId = $this->getUser()->getId();

        $form->setName($name)->setTitle($title)->setUserId($userId);
        $manager->flush();

        $update = new Update(
            'subscribe_update_form',
            json_encode([
                'name' => $name,
                'title' => $title,
                'id' => $id
            ])
        );
        $hub->publish($update);

        return $this->json([
            'data' => (new NormalizeService())->normalizeByGroup($form),
            'count' => 1,
            'message' => 'Form updated'
        ]);
    }
}
