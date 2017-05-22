<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api\Controller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class ReportController extends AbstractRestfulController
{
    private $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function indexAction()
    {
        return new JsonModel([
            "sector_1" => array(
                "Agricultura, ganadería, silvicultura y otros usos de la tierra",
                "144.340460"
            ),
            "colores" => array(
                "#54bdb4",
                "#f44652",
                "#f87652",
                "#9189b8"
            ),
            "descripciones" => array(
                "En el sector se incluyen las emisiones y absorciones de tierras forestales, tierras de cultivo, pastizales, humedales, asentamientos y otras tierras. También incluye las emisiones por la gestión de ganado vivo y de estiércol, las emisiones de los suelos gestionados y las emisiones de las aplicaciones de fertilizantes.",
                "Este sector incluye todas las emisiones de GEI que emanan de la combustión y las fugas de combustibles. Las emisiones de usos no energéticos de combustibles no suelen incluirse en este sector, sino que se declaran dentro de Procesos industriales y uso de productos.",
                "Este sector incluye todas las emisiones de GEI generadas como resultado de la reacción entre materias primas empleadas en diferentes procesos químicos.",
                "En el sector se incluyen las emisiones de GEI que se generan debido a la disposición, tratamiento y gestión de residuos sólidos y aguas residuales."
            ),
            "sector_2" => array(
                "Energía",
                "193.477196"
            ),
            "sector_3" => array(
                "Procesos industriales y uso de productos",
                "16.578409"
            ),
            "sector_4" => array(
                "Residuos",
                "13.899313"
            )
        ]);
    }

    public function getReportBySectoralDistributionAction() 
    {
    	 
    }    
}
