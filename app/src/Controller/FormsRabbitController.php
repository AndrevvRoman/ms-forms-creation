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
class FormsRabbitController extends AbstractController
{
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
}
