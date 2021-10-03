<?php

namespace App\Entity;

use App\Repository\FieldRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
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
    private const INPUT_TYPE_IMAGE = "image";
    private const INPUT_TYPE_RADIO = "radio";
    private const INPUT_TYPE_TEXT = "text";

    //since HTML 5
    private const INPUT_TYPE_COLOR = "color";
    private const INPUT_TYPE_DATE = "date";
    private const INPUT_TYPE_TIME = "time";
    private const INPUT_TYPE_DATETIME = "datetime";
    private const INPUT_TYPE_MOUTH = "month";
    private const INPUT_TYPE_WEEK = "week";
    private const INPUT_TYPE_EMAIL = "email";
    private const INPUT_TYPE_NUMBER = "number";
    private const INPUT_TYPE_RANGE = "range";
    private const INPUT_TYPE_TEL = "tel";
    private const INPUT_TYPE_URL = "url";

    const inputTypes = array(
        Field::INPUT_TYPE_DEFAULT,
        Field::INPUT_TYPE_BUTTON ,
        Field::INPUT_TYPE_CHECKBOX ,
        Field::INPUT_TYPE_FILE ,
        Field::INPUT_TYPE_IMAGE ,
        Field::INPUT_TYPE_RADIO ,
        Field::INPUT_TYPE_TEXT ,
        
        Field::INPUT_TYPE_COLOR ,
        Field::INPUT_TYPE_DATE ,
        Field::INPUT_TYPE_TIME ,
        Field::INPUT_TYPE_DATETIME ,
        Field::INPUT_TYPE_MOUTH ,
        Field::INPUT_TYPE_WEEK ,
        Field::INPUT_TYPE_EMAIL ,
        Field::INPUT_TYPE_NUMBER ,
        Field::INPUT_TYPE_RANGE ,
        Field::INPUT_TYPE_TEL ,
        Field::INPUT_TYPE_URL ,        
    );

    private const RESPONSE_TYPE_DEFAULT = "default";
    private const RESPONSE_TYPE_INTEGER = "integer";
    private const RESPONSE_TYPE_BOOL = "bool";
    private const RESPONSE_TYPE_FLOAT = "float";
    private const RESPONSE_TYPE_STRING = "string";
    private const RESPONSE_TYPE_DATE = "date";
    private const RESPONSE_TYPE_TIME = "time";
    private const RESPONSE_TYPE_DATETIME = "datetime";
    private const RESPONSE_TYPE_COLOR = "color";
    private const RESPONSE_TYPE_FILE = "file";

    const responseTypes = array(
        Field::RESPONSE_TYPE_DEFAULT,
        Field::RESPONSE_TYPE_INTEGER,
        Field::RESPONSE_TYPE_BOOL,
        Field::RESPONSE_TYPE_FLOAT,
        Field::RESPONSE_TYPE_STRING,
        Field::RESPONSE_TYPE_DATE,
        Field::RESPONSE_TYPE_TIME,
        Field::RESPONSE_TYPE_DATETIME,
        Field::RESPONSE_TYPE_COLOR,
        Field::RESPONSE_TYPE_FILE,
    );
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"main"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRequire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"main"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"main"})
     */
    private $placeHolder;

    /**
     * @ORM\ManyToOne(targetEntity=Form::class, inversedBy="fields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idFormFK;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main"})
     */
    private $inputType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main"})
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
