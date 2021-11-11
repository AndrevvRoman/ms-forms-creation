<?php

namespace App\Message;

final class FormChangeMessage
{
    private $name;
    private $title;
    private $userId;
    private $formId;
    public function __construct($name, $title, $userId, $formId)
    {
        $this->name = $name;
        $this->title = $title;
        $this->userId = $userId;
        $this->formId = $formId;
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

    public function getFormId()
    {
        return $this->formId;
    }
}
