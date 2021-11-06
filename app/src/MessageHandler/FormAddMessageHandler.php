<?php

namespace App\MessageHandler;

use App\Entity\Form;
use App\Message\FormAddMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mercure\HubInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Mercure\Update;


final class FormAddMessageHandler implements MessageHandlerInterface
{
    public function __invoke(FormAddMessage $formAdd, HubInterface $hub, ObjectManager $manager)
    {
        echo 'Creating form now ....\n';

        $newForm = new Form();
        $newForm->setName($formAdd->getName())->setTitle($formAdd->getTitle())->setUserId($formAdd->getUserId());
        echo $newForm;
        $manager->persist($newForm);
        $manager->flush();

        $update = new Update(
            'subscribe_add_form',
            json_encode([
                'name' => $newForm->getName(),
                'title' => $newForm->getTitle(),
                'id' => $newForm->getId()
            ])
        );
        $hub->publish($update);
    }
}
