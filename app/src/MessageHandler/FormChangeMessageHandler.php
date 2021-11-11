<?php

namespace App\MessageHandler;

use App\Entity\Form;
use App\Message\FormChangeMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class FormChangeMessageHandler implements MessageHandlerInterface
{
    private HubInterface $hub;
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $entityManager, HubInterface $hub)
    {
        $this->manager = $entityManager;
        $this->hub = $hub;
    }
    public function __invoke(FormChangeMessage $formChangeMessage)
    {
        $form = $this->manager->getRepository(Form::class)->find($formChangeMessage->getFormId());
        if ($form == null) {
            return;
        }

        $form->setName($formChangeMessage->getName())->setTitle($formChangeMessage->getTitle())->setUserId($formChangeMessage->getUserId());
        $this->manager->flush();

        $update = new Update(
            'subscribe_update_form',
            json_encode([
                'name' => $formChangeMessage->getName(),
                'title' => $formChangeMessage->getTitle(),
                'id' => $formChangeMessage->getFormId()
            ])
        );
        $this->hub->publish($update);
    }
}
