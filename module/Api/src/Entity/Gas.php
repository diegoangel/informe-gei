<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Gas
 *
 * @ORM\Table(name="gas")
 * @ORM\Entity
 */
class Gas
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
     * @ORM\Column(name="name_html", type="string", length=255, nullable=true)
     */
    private $nameHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;


}

