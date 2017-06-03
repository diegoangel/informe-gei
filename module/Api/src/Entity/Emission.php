<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Emission
 *
 * @ORM\Table(name="emission", indexes={@ORM\Index(name="fk_emission_1_idx", columns={"activity_id"}), @ORM\Index(name="fk_emission_2_idx", columns={"category_id"}), @ORM\Index(name="fk_emission_3_idx", columns={"gas_id"}), @ORM\Index(name="fk_emission_4_idx", columns={"sector_id"}), @ORM\Index(name="fk_emission_5_idx", columns={"subactivity_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Api\Entity\EmissionRepository")
 */
class Emission
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
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="decimal", precision=11, scale=6, nullable=true)
     */
    private $value;

    /**
     * @var \Activity
     *
     * @ORM\ManyToOne(targetEntity="Activity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_id", referencedColumnName="id")
     * })
     */
    private $activity;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Gas
     *
     * @ORM\ManyToOne(targetEntity="Gas")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gas_id", referencedColumnName="id")
     * })
     */
    private $gas;

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
     * @var \Subactivity
     *
     * @ORM\ManyToOne(targetEntity="Subactivity")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subactivity_id", referencedColumnName="id")
     * })
     */
    private $subactivity;


}

