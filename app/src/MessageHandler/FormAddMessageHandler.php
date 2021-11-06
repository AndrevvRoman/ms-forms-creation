<?php

namespace App\MessageHandler;

use App\Message\FormAddMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FormAddMessageHandler implements MessageHandlerInterface
{
    public function __invoke(FormAddMessage $formAdd)
    {
        echo $formAdd->getTitle();
        echo 'Creating form now ....';
    }
}
