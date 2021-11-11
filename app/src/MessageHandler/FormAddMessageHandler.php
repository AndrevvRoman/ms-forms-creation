<?php

namespace App\MessageHandler;

use App\Entity\Form;
use App\Message\FormAddMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;


final class FormAddMessageHandler implements MessageHandlerInterface
{
    private HubInterface $hub;
    private EntityManagerInterface $manager;
    public function __construct(EntityManagerInterface $entityManager, HubInterface $hub)
    {
        $this->manager = $entityManager;
        $this->hub = $hub;
    }
    public function __invoke(FormAddMessage $formAdd)
    {
        echo 'Creating form now ....';

        $newForm = new Form();
        $newForm->setName($formAdd->getName())->setTitle($formAdd->getTitle())->setUserId($formAdd->getUserId());
        $this->manager->persist($newForm);
        $this->manager->flush();

        $update = new Update(
            'subscribe_add_form',
            json_encode([
                'name' => $newForm->getName(),
                'title' => $newForm->getTitle(),
                'id' => $newForm->getId()
            ])
        );
        $this->hub->publish($update);
    }
}
