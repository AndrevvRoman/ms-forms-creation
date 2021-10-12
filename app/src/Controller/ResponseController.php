<?php

namespace App\Controller;

use App\Entity\Response as EntityResponse;
use App\Service\NormalizeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/api/v1", name="forms")
 */
class ResponseController extends AbstractController
{
    /**
     * @Route("/response/add", name="response", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function add_response(Request $r): Response
    {
        $responseBody = json_decode($r->request->get('responseBody'), true);
        $formId = $r->request->get('formId');

        $em = $this->getDoctrine()->getManager();
        $parentForm = $em->getRepository(EntityResponse::class)->find($formId);

        $newResponse = new EntityResponse();
        $newResponse->setFormIdFK($parentForm)->setResponseBody($responseBody);

        $em->persist($newResponse);
        $em->flush();

        return $this->json([
            'messgae' => 'Response added'
        ]);
    }

    /**
     * @Route("/response/get", name="response", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function get_response(Request $r): Response
    {
        $formId = $r->request->get('formId');
        $limit = $r->request->get('limit');
        $offset = $r->request->get('offset');
        $em = $this->getDoctrine()->getManager();
        $responses = $em->getRepository(EntityResponse::class)->findBy(['formId' => $formId],null,$limit,$offset);

        return $this->json([
            'data' => (new NormalizeService)->normalizeByGroup($responses),
            'count' => count($responses),
            'messgae' => 'Response added'
        ]);
    }
}
