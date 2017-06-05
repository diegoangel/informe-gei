<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subactivity
 *
 * @ORM\Table(name="subactivity", indexes={@ORM\Index(name="fk_subactividad_actividad1_idx", columns={"activity_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Api\Entity\SubactivityRepository")
 */
class Subactivity
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
     * @var \Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     * })
     */
    private $activity;
}
