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
    private const INPUT_TYPE_DATETIME_LOCAL = "datetime-local";
    private const INPUT_TYPE_MOUTH = "month";
    private const INPUT_TYPE_WEEK = "week";
    private const INPUT_TYPE_EMAIL = "email";
    private const INPUT_TYPE_NUMBER = "number";
    private const INPUT_TYPE_RANGE = "range";
    private const INPUT_TYPE_TEL = "tel";
    private const INPUT_TYPE_URL = "url";
    
    //Out of HTML
    private const INPUT_TYPE_TEXTAREA = "textarea";
    private const INPUT_TYPE_RATING = "rating";


    const inputTypes = array(
        Field::INPUT_TYPE_DEFAULT,
        Field::INPUT_TYPE_CHECKBOX ,
        Field::INPUT_TYPE_FILE ,
        Field::INPUT_TYPE_IMAGE ,
        Field::INPUT_TYPE_RADIO ,
        Field::INPUT_TYPE_TEXT ,
        
        Field::INPUT_TYPE_COLOR ,
        Field::INPUT_TYPE_DATE ,
        Field::INPUT_TYPE_TIME ,
        Field::INPUT_TYPE_DATETIME ,
        Field::INPUT_TYPE_DATETIME_LOCAL,
        Field::INPUT_TYPE_MOUTH ,
        Field::INPUT_TYPE_WEEK ,
        Field::INPUT_TYPE_EMAIL ,
        Field::INPUT_TYPE_NUMBER ,
        Field::INPUT_TYPE_RANGE ,
        Field::INPUT_TYPE_TEL ,
        Field::INPUT_TYPE_URL ,    
        Field::INPUT_TYPE_TEXTAREA,
        Field::INPUT_TYPE_RATING,
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
     * @Groups({"main"})
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
     * @Groups({"main"})
     */
    private $idFormFK;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"main"})
     */
    private $inputType;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"main"})
     */
    private $isActive;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"main"})
     */
    private $possbleValues = [];

    /**
     * @ORM\Column(type="integer")
     * @Groups({"main"})
     */
    private $priority;

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

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPossbleValues(): ?array
    {
        return $this->possbleValues;
    }

    public function setPossbleValues(?array $possbleValues): self
    {
        $this->possbleValues = $possbleValues;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }
}
