<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="languages", indexes={
 *      @ORM\Index(name="idx_enabled", columns={"is_enabled"}),
 *      @ORM\Index(name="idx_preferred", columns={"is_preferred"}),
 *      @ORM\Index(name="idx_code", columns={"code"})
 * })
 * @ORM\Entity
 */
class Language
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=24, nullable=false)
     */
    private $code;
    
    /**
     * @var boolean
     * 
     * @ORM\Column(name="is_preferred", type="boolean", nullable=false)
     */
    private $isPreferred = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false)
     */
    private $isEnabled;

    public function __construct(string $name, string $code, bool $enabled = true)
    {
        $this->name = $name;
        $this->code = $code;
        $this->isEnabled = $enabled;
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function __toString()
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setIsEnabled($isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    public function getIsEnabled(): bool
    {
        return $this->isEnabled;
    }

    function getIsPreferred(): bool
    {
        return $this->isPreferred;
    }

    function setIsPreferred(bool $isPreferred): self
    {
        $this->isPreferred = $isPreferred;
        
        return $this;
    }
}
