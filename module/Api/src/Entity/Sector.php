<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sector
 *
 * @ORM\Table(name="sector")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Api\Entity\SectorRepository") 
 */
class Sector
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
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=10, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;


}

