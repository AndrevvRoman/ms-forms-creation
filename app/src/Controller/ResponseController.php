<?php

namespace App\Controller;

use App\Entity\Form;
use App\Entity\Response as EntityResponse;
use App\Service\NormalizeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1", name="response")
 */
class ResponseController extends AbstractController
{
    /**
     * @Route("/response/add", name="response_all", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function add_response(Request $r): Response
    {
        $requestBody = json_decode($r->getContent(), true);

        $formId = $requestBody['formId'];

        $em = $this->getDoctrine()->getManager();
        $parentForm = $em->getRepository(Form::class)->find($formId);

        $newResponse = new EntityResponse();
        $newResponse->setFormIdFK($parentForm)->setResponseBody($requestBody['responseBody']);

        $em->persist($newResponse);
        $em->flush();

        return $this->json([
            'messgae' => 'Response added'
        ]);
    }

    /**
     * @Route("/response/get", name="response_get", methods={"GET"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function get_response(Request $r): Response
    {
        $formId = $r->query->get('formId');
        $limit = $r->query->get('limit');
        $offset = $r->query->get('offset');
        $em = $this->getDoctrine()->getManager();
        $responses = $em->getRepository(Form::class)->find($formId)->getResponses();
        // $responses = $em->getRepository(EntityResponse::class)->findAll();

        return $this->json([
            'data' => (new NormalizeService)->normalizeByGroup($responses),
            'count' => count($responses),
            'messgae' => 'Form founded'
        ]);
    }
}
