<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;
use Api\Entity\Sector;

/**
 * Activity
 *
 * @ORM\Table(name="activity", indexes={@ORM\Index(name="fk_actividad_sector1_idx", columns={"sector_id"})})
 * @ORM\Entity
 */
class Activity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \Sector
     *
     * @ORM\ManyToOne(targetEntity="Sector")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     * })
     */
    private $sector;




    /**
     * Gets the value of id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the value of name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the value of name.
     *
     * @param string $name the name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets the }).
     *
     * @return \Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Sets the }).
     *
     * @param \Sector $sector the sector
     *
     * @return self
     */
    public function setSector(Sector $sector)
    {
        $this->sector = $sector;

        return $this;
    }
}

