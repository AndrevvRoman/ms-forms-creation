<?php

namespace App\MessageHandler;

use App\Entity\Form;
use App\Message\FormDeleteMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class FormDeleteMessageHandler implements MessageHandlerInterface
{
    private HubInterface $hub;
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $entityManager, HubInterface $hub)
    {
        $this->manager = $entityManager;
        $this->hub = $hub;
    }
    public function __invoke(FormDeleteMessage $formDeleteMessage)
    {
        $form = $this->manager->getRepository(Form::class)->find($formDeleteMessage->getFormId());
        if ($form == null) {
            return;
        }

        $this->manager->remove($form);
        $this->manager->flush();

        $update = new Update(
            'subscribe_delete_form',
            json_encode([
                'id' => $formDeleteMessage->getFormId(),
            ])
        );
        $this->hub->publish($update);
    }
}
