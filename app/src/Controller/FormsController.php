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
     * @Route("/forms", name="forms")
     * @Security("is_granted('ROLE_USER')")
     * @return JsonResponse
     */
    public function forms(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $forms = $em->getRepository(Form::class)->findAll();
        $normalizeService = new NormalizeService();
        return $normalizeService->normalizeForms($forms);
    }

    /**
     * @Route("/forms/add", name="forms_add")
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function add_forms(Request $request): Response
    {
        if (!$request->isMethod('post'))
        {
            return new Response(Response::HTTP_FORBIDDEN);
        }
        $name = $request->request->get('name');
        $title = $request->request->get('title');
        $userId = $request->request->get('userId');
        $newForm = new Form();
        $newForm->setName($name);
        $newForm->setTitle($title);
        $newForm->setUserId($userId);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newForm);
        $manager->flush();
        return new Response(Response::HTTP_OK);
    }
    /**
     * @Route("/forms/remove", name="forms_remove")
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function remove_from(Request $request): Response
    {
        if (!$request->isMethod('post'))
        {
            return new Response(Response::HTTP_FORBIDDEN);
        }
        $id = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $form = $manager->getRepository(Form::class)->find($id);
        if ($form == null)
        {
            return new Response(Response::HTTP_NOT_FOUND);    
        }
        $manager->remove($form);
        $manager->flush();
        return new Response(Response::HTTP_OK);
    }
    /**
     * @Route("/forms/update", name="forms_update")
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function update_from(Request $request): Response
    {
        if (!$request->isMethod('post'))
        {
            return new Response(Response::HTTP_FORBIDDEN);
        }
        $id = $request->request->get('id');
        $manager = $this->getDoctrine()->getManager();
        $form = $manager->getRepository(Form::class)->find($id);
        if ($form == null)
        {
            return new Response(Response::HTTP_NOT_FOUND);    
        }
        $name = $request->request->get('name');
        $title = $request->request->get('title');
        $userId = $request->request->get('userId');
        $form->setName($name)->setTitle($title)->setUserId($userId);
        $manager->flush();
        return new Response(Response::HTTP_OK);
    }
}
