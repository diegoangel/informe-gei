<?php

namespace Api\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * IndicatorValue
 *
 * @ORM\Table(name="indicator_value", indexes={@ORM\Index(name="fk_indicador_valor_indicador1_idx", columns={"indicator_id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Api\Entity\IndicatorValueRepository")  
 */
class IndicatorValue
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
     * @var \Indicator
     *
     * @ORM\ManyToOne(targetEntity="Indicator")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="indicator_id", referencedColumnName="id")
     * })
     */
    private $indicator;


}

