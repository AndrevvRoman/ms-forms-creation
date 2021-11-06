<?php

namespace App\Message;

final class FormAddMessage
{
    private $name;
    private $title;
    private $userId;

    public function __construct($name, $title, $userId)
    {
        $this->name = $name;
        $this->title = $title;
        $this->userId = $userId;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUserId()
    {
        return $this->userId;
    }
}
