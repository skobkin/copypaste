<?php

namespace Skobkin\Bundle\CopyPasteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Paste
 *
 * @ORM\Table(
 *      name="paste",
 *      indexes={
 *          @ORM\Index(name="idx_expire", columns={"date_exp"})
 *      }
 * )
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
     * @ORM\Column(name="code", type="text", nullable=false)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="code_comment", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="lang", type="integer", nullable=false)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=128, nullable=true)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=48, nullable=true)
     */
    private $author;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_pub", type="datetime", nullable=false)
     */
    private $datePublished;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_exp", type="datetime", nullable=true)
     */
    private $dateExpire;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=48, nullable=false)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=16, nullable=true)
     */
    private $secret;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Paste
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Paste
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get codeComment
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set language
     *
     * @param integer $language
     * @return Paste
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return integer 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Paste
     */
    public function setFilename($filename)
    {
        $this->fileName = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->fileName;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Paste
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set publication date
     *
     * @param \DateTime $datePublished
     * @return Paste
     */
    public function setDatePublished($datePublished)
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    /**
     * Get publication date
     *
     * @return \DateTime 
     */
    public function getDatePublished()
    {
        return $this->datePublished;
    }

    /**
     * Set dateExp
     *
     * @param \DateTime $dateExpire
     * @return Paste
     */
    public function setDateExpire($dateExpire)
    {
        $this->dateExpire = $dateExpire;

        return $this;
    }

    /**
     * Get dateExp
     *
     * @return \DateTime 
     */
    public function getDateExpire()
    {
        return $this->dateExpire;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return Paste
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return Paste
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }
    
    /**
     * Check if copypaste is private
     * 
     * @return boolean
     */
    public function isPrivate()
    {
        return ($this->secret === null) ? false : true;
    }
}
