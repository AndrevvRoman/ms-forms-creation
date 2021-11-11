<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Form;
use App\Message\FormAddMessage;
use App\Message\FormChangeMessage;
use App\Message\FormDeleteMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Service\NormalizeService;


use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;


/**
 * @Route("/api/v1/rabbit", name="forms")
 */
class FormsRabbitController extends AbstractController
{
    /**
     * @Route("/forms/add", name="form_add_rabbit", methods={"POST"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function add_forms_rabbit(Request $r, MessageBusInterface $bus): Response
    {
        $data = json_decode($r->getContent(), true);
        $name = $data['name'];
        $title = $data['title'];
        $userId = $this->getUser()->getId();
        $bus->dispatch(new FormAddMessage($name, $title, $userId));

        return new Response('You form has been placed');
    }

        /**
     * @Route("/forms/remove", name="form_remove_rabbit", methods={"DELETE"})
     * @Security("is_granted('ROLE_USER')")
     * @return HttpResponse
     */
    public function remove_from_rabbit(Request $request, MessageBusInterface $bus): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];

        $bus->dispatch(new FormDeleteMessage($id));

        return new Response('You form has been removed');
    }

    /**
     * @Route("/forms/update", name="form_update_rabbit", methods={"PATCH"})
     * @Security("is_granted('ROLE_USER')")
     * 
     * @return HttpResponse
     */
    public function update_from_rabbit(Request $request,  MessageBusInterface $bus): Response
    {
        $data = json_decode($request->getContent(), true);
        $id = $data['id'];
        $name = $data['name'];
        $title = $data['title'];

        $userId = $this->getUser()->getId();

        $bus->dispatch(new FormChangeMessage($name,$title,$userId,$id));

        return new Response("Form will change soon");
    }
}
