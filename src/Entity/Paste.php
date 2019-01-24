<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="copypastes", indexes={
 *      @ORM\Index(name="idx_expire", columns={"date_expire"})
 * })
 * @ORM\Entity
 */
class Paste
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
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description = null;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=128, nullable=true)
     */
    private $fileName = null;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=48, nullable=true)
     */
    private $author = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_publish", type="datetime", nullable=false)
     */
    private $datePublished;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_expire", type="datetime", nullable=true)
     */
    private $dateExpire = null;

    /**
     * @var string
     *
     * @Assert\Ip
     * @ORM\Column(name="ip", type="string", length=48, nullable=false)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=16, nullable=true)
     */
    private $secret = null;

    public function __construct()
    {
        $this->datePublished = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function __toString()
    {
        return (string) $this->id;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setLanguage(Language $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setFilename(string $filename): self
    {
        $this->fileName = $filename;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->fileName;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function getDatePublished(): \DateTime
    {
        return $this->datePublished;
    }

    public function setDateExpire(\DateTime $dateExpire): self
    {
        $this->dateExpire = $dateExpire;

        return $this;
    }

    public function getDateExpire(): ?\DateTime
    {
        return $this->dateExpire;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function setSecret(?string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function getSecret(): ?string
    {
        return $this->secret;
    }

    public function isPrivate(): bool
    {
        return ($this->secret === null) ? false : true;
    }
}
