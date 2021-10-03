<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\NormalizeService;


class FormsController extends AbstractController
{
    /**
     * @Route("/forms", name="forms", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function forms(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $forms = $em->getRepository(Form::class)->findAll();
        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($forms)
        ]);
    }

    /**
     * @Route("/forms", name="forms", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function form(Request $request): Response
    {
        $id = $request->query->get('id');
        $em = $this->getDoctrine()->getManager();
        $form = $em->getRepository(Form::class)->find($id);
        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($form),
            'message' => 'Form founded'
        ]);
    }

    /**
     * @Route("/forms/add", name="forms_add", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function add_forms(Request $request): Response
    {
        $name = $request->request->get('name');
        $title = $request->request->get('title');
        $userId = $request->request->get('userId');

        $newForm = new Form();
        $newForm->setName($name)->setTitle($title)->setUserId($userId);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newForm);
        $manager->flush();

        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($newForm),
            'message' => 'Form created'
        ]);
    }
    /**
     * @Route("/forms/remove", name="forms_remove", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function remove_from(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $manager = $this->getDoctrine()->getManager();
        $form = $manager->getRepository(Form::class)->find($id);
        if ($form == null)
        {
            return $this->json([
                'message' => 'Form not found'
            ]);
        }

        $manager->remove($form);
        $manager->flush();

        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($form),
            'message' => 'Form deleted'
        ]);
    }
    /**
     * @Route("/forms/update", name="forms_update", methods={"PATCH"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function update_from(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $manager = $this->getDoctrine()->getManager();
        $form = $manager->getRepository(Form::class)->find($id);
        if ($form == null)
        {
            return $this->json([
                'message' => 'Form not found'
            ]);  
        }

        $name = $data['name'];
        $title = $data['title'];
        $userId = $data['userId'];

        $form->setName($name)->setTitle($title)->setUserId($userId);
        $manager->flush();
        
        return $this->json([
            'data' =>  (new NormalizeService())->normalizeByGroup($form),
            'message' => 'Form updated'
        ]);
    }
}
