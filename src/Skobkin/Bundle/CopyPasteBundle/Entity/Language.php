<?php

namespace Skobkin\Bundle\CopyPasteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
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



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __toString()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Lang
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Lang
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     * @return Lang
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean 
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }
    
    /**
     * Get isPreferred
     * 
     * @return boolean
     */
    function getIsPreferred()
    {
        return $this->isPreferred;
    }

    /**
     * Set isPreferred
     * 
     * @param boolean $isPreferred
     */
    function setIsPreferred($isPreferred)
    {
        $this->isPreferred = $isPreferred;
        
        return $this;
    }
}
