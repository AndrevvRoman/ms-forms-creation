<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Proxies\__CG__\App\Entity\InputType;

/**
 * @ORM\Entity(repositoryClass=FieldRepository::class)
 */
class Field
{
    private const INPUT_TYPE_DEFAULT = "default";
    private const RESPONSE_TYPE_DEFAULT = "default";
    const inputTypes = array(Field::INPUT_TYPE_DEFAULT);
    const responseTypes = array(Field::RESPONSE_TYPE_DEFAULT);
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRequire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $placeHolder;

    /**
     * @ORM\ManyToOne(targetEntity=Form::class, inversedBy="fields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idFormFK;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inputType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $responseType;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsRequire(): ?bool
    {
        return $this->isRequire;
    }

    public function setIsRequire(bool $isRequire): self
    {
        $this->isRequire = $isRequire;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPlaceHolder(): ?string
    {
        return $this->placeHolder;
    }

    public function setPlaceHolder(?string $placeHolder): self
    {
        $this->placeHolder = $placeHolder;

        return $this;
    }

    public function getIdFormFK(): ?Form
    {
        return $this->idFormFK;
    }

    public function setIdFormFK(?Form $idFormFK): self
    {
        $this->idFormFK = $idFormFK;

        return $this;
    }

    public function validateInputType($inputType)
    {
        return in_array($inputType,Field::inputTypes);
    }
    public function validateResponseType($responseType)
    {
        return in_array($responseType,Field::responseTypes);
    }

    public function getInputType(): ?string
    {
        return $this->inputType;
    }

    public function setInputType(string $inputType): self
    {
        if ($this->validateInputType($inputType))
        {
            $this->inputType = $inputType;
        }

        return $this;
    }

    public function getResponseType(): ?string
    {
        return $this->responseType;
    }

    public function setResponseType(string $responseType): self
    {
        if ($this->validateResponseType($responseType))
        {
            $this->responseType = $responseType;
        }

        return $this;
    }
}
