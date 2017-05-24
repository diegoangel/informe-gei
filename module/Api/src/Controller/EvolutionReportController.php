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

    public function getSectoralEvolutionAction()
    {
        $params = $this->params()->fromRoute();

        $sector_id  = (int)$params['sector_id'];

        $response = [];

        // TRAIGO LOS SECTORES CON SUS COLORES

        $sql = "SELECT s.nombre, s.color
            FROM sector s 
            WHERE 1 
            AND s.id = ? 
            ORDER BY s.nombre ";

        $params = [
            $sector_id,
        ];

        $statement = $this->db->createStatement($sql,$params);

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

        $sector_id = (int)$params['sector_id'];

        $response = [];

        $sql = "SELECT sub.nombre
                FROM subactividad sub
                INNER JOIN actividad a ON (a.id = sub.actividad_id)
                INNER JOIN sector s ON (a.sector_id = s.id)
                WHERE 1 
                AND s.id = ?
                ORDER BY sub.nombre";

        $params = [
            $sector_id,
        ];


        $statement = $this->db->createStatement($sql,$params);
        
        $arrSubactividades = $statement->execute();


        if (!$arrSubactividades->isQueryResult()) {
            $this->response->setStatusCode(404);
        }

        // TRAIGO LOS VALORES POR ANO

        $sql = "SELECT sub.nombre as sector, e.ano, SUM(e.valor) as total
                FROM emision e
                INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
                INNER JOIN sector s ON (e.sector_id = s.id)
                AND s.id = ? 
                GROUP BY e.ano, sub.nombre";

        


        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA

        $arrAnos = [];
        $arrValores = [];
        $arrColores = [];

        for($i=1990;$i<=2014;$i++)
        {
            $arrAnos[] = $i;
        }

        $column = 2;


        while ($subactividad = $arrSubactividades->next())
        {

            $response['column_'.$column][] = $subactividad['nombre'];
            $response['groups'][] = $subactividad['nombre'];

            foreach($arrAnos as $ano)
            {
                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                
                $statement = $this->db->createStatement($sql,$params);
        
                $arrValoresCrudo = $statement->execute();

                if (!$arrValoresCrudo->isQueryResult()) {
                    $this->response->setStatusCode(404);
                }
                // HASTA ACA

                $response['column_'.$column][] = Utils::returnSectorAno($arrValoresCrudo,$subactividad['nombre'],$ano);
                
            }

            $column++;
        }


        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;


        return new JsonModel($response);
  
    }

    public function getSectoralEvolutionSubactivityCategoryAction()
    {
        $params = $this->params()->fromRoute();

        $sector_id = (int)$params['sector_id'];
        $subactividad_id = (int)$params['subactividad_id'];

        $response = [];

        $sql = "SELECT DISTINCT c.nombre
                FROM emision e
                INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
                INNER JOIN sector s ON (e.sector_id = s.id) 
                INNER JOIN categoria c ON (e.categoria_id = c.id)
                WHERE 1 
                AND s.id = ?
                AND sub.id = ? 
                ORDER BY c.nombre"; 


        $params = [
           
            $sector_id,
            $subactividad_id
        ];

        $statement = $this->db->createStatement($sql,$params);
        $arrCategorias = $statement->execute();



        $sql = "SELECT sub.nombre as subcategoria, e.ano, c.nombre, e.valor
                FROM emision e
                INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
                INNER JOIN sector s ON (e.sector_id = s.id) 
                INNER JOIN categoria c ON (e.categoria_id = c.id)
                WHERE 1
                AND s.id = ? 
                AND sub.id = ? 
                GROUP BY e.ano, c.nombre";

        $params = [
            $sector_id,
            $subactividad_id,
        ];

        


        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA
        $arrAnos = array();
        $arrValores = array();
        $arrColores = array();


        for($i=1990;$i<=2014;$i++)
        {
            $arrAnos[] = $i;
        }

        $column = 2;

        // // // pr($arrCategorias);
        // // // pr($arr);

        while($categoria = $arrCategorias->next())
        {

            $response['column_'.$column][] = $categoria['nombre'];
            $response['groups'][] = $categoria['nombre'];

            foreach($arrAnos as $ano)
            {

                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                $statement = $this->db->createStatement($sql,$params);
                $arrValoresCrudo = $statement->execute();
                
                if (!$arrValoresCrudo->isQueryResult()) {
                    $this->response->setStatusCode(404);
                }
                // HASTA ACA


                $response['column_'.$column][] = Utils::returnCategoriaAno($arrValoresCrudo,$categoria['nombre'],$ano);
            }

            $column++;
        }


        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;
        
        return new JsonModel($response);

    }
}