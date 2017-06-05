<?php

namespace Api\Controller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Validator\StaticValidator;
use Api\Helper\Utils;
use Api\Entity\Emission;
use Api\Entity\EmissionRepository;
use Api\Entity\Sector;

class DistributionReportController extends AbstractRestfulController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * DISTRIBUCION DE TODOS LOS SECTORES
     */
    public function getWholeSectoralDistributionAction()
    {
        $year = (int)$this->params()->fromRoute('year');

        $response = [];

        $results = $this->entityManager->getRepository(Emission::class)
            ->findBySector($year);

        $i = 1;

        foreach ($results as $result) {
            $response['sector_'.$i][]      = $result['name'];
            $response['sector_'.$i][]      = $result['total'];
            $response['colores'][]         = $result['color'];
            $response['descripciones'][]   = $result['description'];
            $i++;
        }

        return new JsonModel($response);
    }

    /**
     *  DISTRIBUCION POR SECTOR
     */
    public function getSectoralDistributionAction()
    {
        $year = (int)$this->params()->fromRoute('year');
        $sector = (int)$this->params()->fromRoute('sector');

        $response = [];
        $arrGraphData = [];

        $results = $this->entityManager->getRepository(Emission::class)
            ->findBySectorAndActivity($year, $sector);

        $totalActivities = 0;

        foreach ($results as $result) {
            $arrActividades = [
                'label' => $result['name'],
                'value' => round($result['total'], 2)
            ];

            $totalActivities += $result['total'];

            // EN CADA ACTIVIDAD HAGO QUE HACER EL SEARCH DE LA SUBACTIVIDAD
            $results2 = $this->entityManager->getRepository(Emission::class)
                ->findBySubactivity($year, $sector, $result['activity']);

            if (count($results2) > 0) {
                $arrSubactividades = [];

                $i = 0;

                foreach ($results2 as $result2) {
                    $arrSubactividades[$i] = [
                        'label' => $result2['nombre'],
                        'value' => round($result2['total'], 2)
                    ];


                    if ($sector == 3 || $sector == 4) {
                        continue;
                    }

                    // TODO: ACA EN CADA SUBACTIVIDAD TENDRIA QUE HACER EL SEARCH DE LA CATEGORIA
                    $results3 = $this->entityManager->getRepository(Emission::class)
                        ->findByCategory($year, $sector, $result['activity'], $result2['subactivity']);

                    if (count($results3) > 0) {
                        $arrCategorias = [];

                        foreach ($results3 as $result3) {
                            $arrCategorias[] = [
                                'label'=>$result3['nombre'],
                                'value'=>round($result3['total'], 2)
                            ];
                        }
                        $arrSubactividades[$i]['inner'] = $arrCategorias;
                    }
                    $i++;
                }

                $arrActividades['inner'] = $arrSubactividades;
            }
            
            $arrGraphData[] = $arrActividades;
        }


        $response['graph_data'] = $arrGraphData;
        $response['totalActivities'] = $totalActivities;

        /**
         *  BUSCO LA INFO DEL SECTOR
         */
        $results = $this->entityManager->getRepository(Sector::class)->getSector($sector);

        $response['sector'] = $results[0];

        return new JsonModel($response);
    }

    /**
     *
     */
     public function getGasesDistributionAction()
     {
         $year = (int)$this->params()->fromRoute('year');

         $response = [];

         $results = $this->entityManager->getRepository(Emission::class)->findByGases($year);

         $response['gases'][] = 'x';
         $response['valores'][] = 'Gases';

         foreach ($results as $result) {
             $response['gases'][]   = (strpos($result['name'], ',')) ? '"'.$result['name'].'"' : $result['name'];
             $response['valores'][] = round($result['total']);
             $response['colores'][] = $result['color'];
         }

         return new JsonModel($response);
     }

    public function getSectoralGasesDistributionAction()
    {
        $year = (int)$this->params()->fromRoute('year');

        $response = [];

        $arrGases = $this->entityManager->getRepository(Emission::class)->findByGases($year);

        $arrSectores = $this->entityManager->getRepository(Sector::class)
            ->getSectorsNameOrderedyName();

        $arr = $this->entityManager->getRepository(Emission::class)
            ->findByGasesAndSector($year);

        $column = 2;

        foreach ($arrSectores as $sector) {
            $response['column_'.$column][] = $sector['name'];

            foreach ($arrGases as $gas) {
                $response['column_'.$column][] = Utils::returnSectorGas($arr, $sector['name'], $gas['name']);
            }
            $column++;
        }

        $arrReturnGases = ['x'];
        
        foreach ($arrGases as $gas) {
            $arrReturnGases[] = $gas['name'];
        }

        $response['column_1'] = $arrReturnGases;
      
        return new JsonModel($response);
    }
}
