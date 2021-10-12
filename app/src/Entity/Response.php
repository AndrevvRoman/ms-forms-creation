<?php

namespace App\Entity;

use App\Repository\ResponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResponseRepository::class)
 */
class Response
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"main"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Form::class, inversedBy="responses")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"main"})
     */
    private $formIdFK;

    /**
     * @ORM\Column(type="json")
     * @Groups({"main"})
     */
    private $responseBody = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormIdFK(): ?Form
    {
        return $this->formIdFK;
    }

    public function setFormIdFK(?Form $formIdFK): self
    {
        $this->formIdFK = $formIdFK;

        return $this;
    }

    public function getResponseBody(): ?array
    {
        return $this->responseBody;
    }

    public function setResponseBody(array $responseBody): self
    {
        $this->responseBody = $responseBody;

        return $this;
    }
}
