<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return JsonResponse
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $forms = $em->getRepository(Form::class)->findAll();
        $normalizeService = new NormalizeService();
        return $normalizeService->normalizeForms($forms);
    }

    /**
     * @Route("/forms/add", name="forms_add")
     * @return HttpResponse
     */
    public function add_forms(Request $request): Response
    {
        $name = $request->query->get('name');
        $title = $request->query->get('title');
        $userId = $request->query->get('userId');
        $newForm = new Form();
        $newForm->setName($name);
        $newForm->setTitle($title);
        $newForm->setUserId($userId);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($newForm);
        $manager->flush();
        return new Response(Response::HTTP_OK);
    }
}
