<?php

namespace App\Message;

final class FormAddMessage
{
    private $name;
    private $title;

    public function __construct($name, $title)
    {
        $this->name = $name;
        $this->title = $title;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }
}
