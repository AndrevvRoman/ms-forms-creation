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
        if ($parentForm == null)
        {
            return $this->json([
                'data' => [],
                'message' => 'Form not found',
                'count' => 0
            ]);  
        }

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
        $data = json_decode($r->getContent(), true);
        $formId = $data['formId'];
        $limit = $data['limit'];
        $offset = $data['offset'];
        $em = $this->getDoctrine()->getManager();
        $responses = $em->getRepository(EntityResponse::class)->findBy(array('formIdFK' => $formId),null,$limit,$offset);

        return $this->json([
            'data' => (new NormalizeService)->normalizeByGroup($responses),
            'count' => count($responses), //TODO считать сколько всего респонсов, а не в текущем ответе
            'messgae' => 'Form and responses'
        ]);
    }

    /**
     * @Route("/response/remove", name="response_remove", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     */
    public function remove_response(Request $r): Response
    {
        // Добавить удаление по массиву id,а не по одному
        $data = json_decode($r->getContent(), true);
        $responseId = $data['id'];
        $em = $this->getDoctrine()->getManager();
        $response = $em->getRepository(EntityResponse::class)->find($responseId);
        if ($response == null)
        {
            return $this->json([
                'data' => [],
                'count' => 0,
                'messgae' => 'Response not founded'
            ]);
        }

        $em->remove($response);
        $em->flush();

        return $this->json([
            'data' => (new NormalizeService)->normalizeByGroup($response),
            'count' => 1,
            'messgae' => 'Response deleted'
        ]);
    }
}
