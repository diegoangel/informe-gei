<?php

namespace Api\Controller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Api\Helper\Utils;

/**
 *
 */
class EvolutionReportController extends AbstractRestfulController
{
    /**
     *
     */
    private $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function getWholeSectoralEvolutionAction()
    {
        $params = $this->params()->fromRoute();

        $response = [];

        // TRAIGO LOS SECTORES CON SUS COLORES

        $sql = "SELECT s.nombre, s.color
            FROM sector s 
            WHERE 1 
            ORDER BY s.nombre ";

        $statement = $this->db->createStatement($sql);

        $arrSectores = $statement->execute();

        if (!$arrSectores->isQueryResult()) {
            $this->response->setStatusCode(404);
        }

        // TRAIGO LOS VALORES POR ANO

        $sql = "SELECT s.nombre as sector, e.ano, sum(e.valor) as total
            FROM emision e
            LEFT JOIN sector s ON (e.sector_id = s.id)
            where 1 
            GROUP BY e.ano, s.nombre";

        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA

        $arrAnos = [];
        $arrValores = [];
        $arrColores = [];

        for($i=1990;$i<=2014;$i++)
        {
            $arrAnos[] = $i;
        }

        $column = 2;

        while ($sector = $arrSectores->next())
        {
            $response['column_'.$column][] = $sector['nombre'];
            $response['colores'][] =  $sector['color'];

            foreach($arrAnos as $ano)
            {
                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                $statement = $this->db->createStatement($sql);
                $arrValoresCrudo = $statement->execute();

                if (!$arrValoresCrudo->isQueryResult()) {
                    $this->response->setStatusCode(404);
                }
                // HASTA ACA

                $response['column_'.$column][] = Utils::returnSectorAno($arrValoresCrudo,$sector['nombre'],$ano);
                
            }

            $column++;
        }


        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;


        return new JsonModel($response);

    }


    public function getSectoralEvolutionSubactivityAction()
    {
        $params = $this->params()->fromRoute();

        $ano = (int)$params['ano'];

        // TODO: insert remaining logic from ajax.php file        

        return new JsonModel($response);      
    }

    public function getSectoralEvolutionSubactivityCategoryAction()
    {
        $params = $this->params()->fromRoute();

        $ano = (int)$params['ano'];

        // TODO: insert remaining logic from ajax.php file        

        return new JsonModel($response);      
    }
}