<?php

namespace App\Message;

final class FormDeleteMessage
{
    private $formId;
    public function __construct($formId)
    {
        $this->formId = $formId;
    }

    public function getFormId()
    {
        return $this->formId;
    }
}
