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
    private const INPUT_TYPE_BUTTON = "button";
    private const INPUT_TYPE_CHECKBOX = "checkbox";
    private const INPUT_TYPE_FILE = "file";
    private const INPUT_TYPE_HIDDEN = "hidden";
    private const INPUT_TYPE_IMAGE = "image";
    private const INPUT_TYPE_PASSWORD = "password";
    private const INPUT_TYPE_RADIO = "radio";
    private const INPUT_TYPE_RESET = "reset";
    private const INPUT_TYPE_SUBMIT = "submit";
    private const INPUT_TYPE_TEXT = "text";

    //since HTML 5
    private const INPUT_TYPE_COLOR = "color";
    private const INPUT_TYPE_DATE = "date";
    private const INPUT_TYPE_TIME = "time";
    private const INPUT_TYPE_DATETIME = "datetime";
    private const INPUT_TYPE_DATETIME_LOCAL = "datetime-local";
    private const INPUT_TYPE_MOUTH = "month";
    private const INPUT_TYPE_WEEK = "week";
    private const INPUT_TYPE_EMAIL = "email";
    private const INPUT_TYPE_NUMBER = "number";
    private const INPUT_TYPE_RANGE = "range";
    private const INPUT_TYPE_SEARCH = "search";
    private const INPUT_TYPE_TEL = "tel";
    private const INPUT_TYPE_URL = "url";

    const inputTypes = array(
        Field::INPUT_TYPE_DEFAULT,
        Field::INPUT_TYPE_BUTTON ,
        Field::INPUT_TYPE_CHECKBOX ,
        Field::INPUT_TYPE_FILE ,
        Field::INPUT_TYPE_HIDDEN ,
        Field::INPUT_TYPE_IMAGE ,
        Field::INPUT_TYPE_PASSWORD ,
        Field::INPUT_TYPE_RADIO ,
        Field::INPUT_TYPE_RESET ,
        Field::INPUT_TYPE_SUBMIT ,
        Field::INPUT_TYPE_TEXT ,
        
        Field::INPUT_TYPE_COLOR ,
        Field::INPUT_TYPE_DATE ,
        Field::INPUT_TYPE_TIME ,
        Field::INPUT_TYPE_DATETIME ,
        Field::INPUT_TYPE_DATETIME_LOCAL ,
        Field::INPUT_TYPE_MOUTH ,
        Field::INPUT_TYPE_WEEK ,
        Field::INPUT_TYPE_EMAIL ,
        Field::INPUT_TYPE_NUMBER ,
        Field::INPUT_TYPE_RANGE ,
        Field::INPUT_TYPE_SEARCH ,
        Field::INPUT_TYPE_TEL ,
        Field::INPUT_TYPE_URL ,        
    );

    private const RESPONSE_TYPE_DEFAULT = "default";
    
    const responseTypes = array(
        Field::RESPONSE_TYPE_DEFAULT
    );
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
