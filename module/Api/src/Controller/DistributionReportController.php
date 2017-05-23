<?php

namespace Api\Controller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Api\Helper\Utils;

/**
 *
 */
class DistributionReportController extends AbstractRestfulController
{
    /**
     *
     */
    private $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    /**
     *
     */
    public function indexAction()
    {
        $params = $this->params()->fromRoute();

        $ano = (int)$params['ano'];

        $response = [];

        $sql = 'SELECT SUM(e.valor) as total, e.sector_id, s.nombre, s.color, s.descripcion
            FROM emision e 
            INNER JOIN sector s ON e.sector_id = s.id
            WHERE e.ano = ?
            GROUP BY e.sector_id';
        
        $parameters = [
            $ano,
        ];

        $statement = $this->db->createStatement($sql, $parameters);

        $results = $statement->execute();

        if (!$results->isQueryResult()) {
            $this->response->setStatusCode(404);
        }

        $i = 1;

        while ($a = $results->next()) {
            $response['sector_'.$i][]      = $a['nombre'];
            $response['sector_'.$i][]      = $a['total'];
            $response['colores'][]         = $a['color'];
            $response['descripciones'][]   = $a['descripcion'];

            $i++;
        }

        return new JsonModel($response);
    }

    /**
     *
     */
    public function getSectoralDistributionAction()
    {
        $params = $this->params()->fromRoute();

        $response = [];

        $ano = (int)$params['ano'];
        $sector_id  = (int)$params['sector_id'];

        $sql = 'SELECT SUM(e.valor) as total, a.id, a.nombre
            FROM emision e 
            INNER JOIN sector s ON e.sector_id = s.id
            INNER JOIN actividad a ON a.id = e.actividad_id
            WHERE 1 
            AND e.sector_id = ?
            AND e.ano = ?
            GROUP BY a.id
            ORDER BY a.nombre';

        $parameters = [
            $sector_id,
            $ano,
        ];

        $statement = $this->db->createStatement($sql, $parameters);

        $results = $statement->execute();

        while ($a = $results->next()) {
            $arrActividades = [
                'label' => $a['nombre'],
                'value' => round($a['total'])
            ];

            // TODO: ACA EN CADA ACTIVIDAD TENDRIA QUE HACER EL SEARCH DE LA SUBACTIVIDAD
            $sql = 'SELECT SUM(e.valor) as total, a.id, a.nombre
                FROM emision e 
                INNER JOIN subactividad a ON a.id = e.subactividad_id
                WHERE 1 
                AND e.sector_id = ?
                AND e.actividad_id = ?
                AND e.ano = ?
                GROUP BY a.id
                ORDER BY a.nombre';

            $parameters = [
                $sector_id,
                $a['id'],
                $ano,
            ];

            $statement = $this->db->createStatement($sql, $parameters);

            $results = $statement->execute();

            if ($results->isQueryResult()) {
                $arrSubactividades = [];

                $i = 0;

                while ($a2 = $results->next()) {
                    $arrSubactividades[$i] = [
                        'label' => $a2['nombre'],
                        'value' => round($a2['total'])
                    ];

                    // TODO: ACA EN CADA SUBACTIVIDAD TENDRIA QUE HACER EL SEARCH DE LA CATEGORIA
                    $sql = 'SELECT SUM(e.valor) as total, a.id, a.nombre
                        FROM emision e 
                        INNER JOIN categoria a ON a.id = e.categoria_id
                        WHERE 1 
                        AND e.sector_id = ?
                        AND e.actividad_id = ?
                        AND e.subactividad_id = ?
                        AND e.ano = ?
                        GROUP BY a.id
                        ORDER BY a.nombre';

                    $parameters = [
                        $sector_id,
                        $a['id'],
                        $a2['id'],
                        $ano,
                    ];

                    $statement = $this->db->createStatement($sql, $parameters);

                    $results = $statement->execute();

                    if ($results->isQueryResult()) {
                        $arrCategorias = [];

                        while ($a3 = $results->next()) {
                            $arrCategorias[] = [
                                'label'=>$a3['nombre'],
                                'value'=>round($a3['total'])
                            ];
                        }
                        $arrSubactividades[$i]['inner'] = $arrCategorias;
                    }
                    $i++;
                }
                $arrActividades['inner'] = $arrSubactividades;
            }
            $response[] = $arrActividades;
        }
        return new JsonModel($response);
    }

    /**
     *
     */
     public function getGasesDistributionAction()
     {
         $params = $this->params()->fromRoute();

         $ano = (int)$params['ano'];

         $response = [];

         $sql = 'SELECT e.gas_id, g.nombre, g.color, sum(e.valor) as total
            FROM emision e
            LEFT JOIN gas g ON (e.gas_id = g.id)
            where e.ano = ? GROUP BY e.gas_id ORDER BY total DESC';

         $parameters = [
            $ano,
        ];

         $statement = $this->db->createStatement($sql, $parameters);

         $results = $statement->execute();

         $response['gases'][] = 'x';
         $response['valores'][] = 'Gases';

         while ($a = $results->next()) {
             $response['gases'][]   = (strpos($a['nombre'], ',')) ? '"'.$a['nombre'].'"' : $a['nombre'];
             $response['valores'][] = round($a['total']);
             $response['colores'][] = $a['color'];
         }
         return new JsonModel($response);
     }

    public function getSectoralGasesDistributionAction()
    {
        $params = $this->params()->fromRoute();

        $ano = (int)$params['ano'];

        $response = [];

        // PRIMERA FILA LA DE LOS GASES

        $sql = 'SELECT g.nombre, sum(e.valor) as total
            FROM emision e
            LEFT JOIN gas g ON (e.gas_id = g.id)
            where e.ano = ? GROUP BY e.gas_id ORDER BY total DESC';

        $parameters = [
            $ano,
        ];

        $statement = $this->db->createStatement($sql, $parameters);

        $arrGases = $statement->execute();

        $sql = 'SELECT s.nombre
            FROM sector s 
            ORDER BY s.nombre';

        $statement = $this->db->createStatement($sql);

        $arrSectores = $statement->execute();

        $sql = 'SELECT s.nombre as sector, g.nombre as gas, sum(e.valor) as total
            FROM emision e
            LEFT JOIN gas g ON (e.gas_id = g.id)
            LEFT JOIN sector s ON (e.sector_id = s.id)
            where e.ano = ? GROUP BY e.gas_id, e.sector_id ORDER BY total DESC';

        $parameters = [
            $ano,
        ];

        $statement = $this->db->createStatement($sql, $parameters);

        $arr = $statement->execute();

        $column = 2;

        while ($sector = $arrSectores->next()) {
            $response['column_'.$column][] = $sector;

            while ($gas = $arrGases->next()) {
              $response['column_'.$column][] = Utils::returnSectorGas($arr, $sector, $gas['nombre']);
            }
            $column++;
        }

        $arrReturnGases = ['x'];
        
        while ($gas = $arrGases->next()) {
            $arrReturnGases[] = $gas['nombre'];
        }

        $response['column_1'] = $arrReturnGases;
      
        return new JsonModel($response);
    }
}
