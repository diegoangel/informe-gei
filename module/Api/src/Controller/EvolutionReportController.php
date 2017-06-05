<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Api\Helper\Utils;
use Api\Entity\Emission;
use Api\Entity\Sector;
use Api\Entity\Subactivity;

/**
 *
 */
class EvolutionReportController extends AbstractRestfulController
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;

    const START_YEAR = 1990;

    const END_YEAR = 2014;

    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getWholeSectoralEvolutionAction()
    {
        $response = [];

        // TRAIGO LOS SECTORES CON SUS COLORES

        $arrSectores = $this->entityManager->getRepository(Sector::class)
            ->getSectorsOrderedyName();

        // TRAIGO LOS VALORES POR ANO
        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA

        $arrAnos = [];
        $arrValores = [];
        $arrColores = [];

        for ($i = self::START_YEAR; $i <= self::END_YEAR; $i++) {
            $arrAnos[] = $i;
        }

        $column = 2;

        foreach ($arrSectores as $sector) {
            $response['column_'.$column][] = $sector['name'];
            $response['colores'][] = $sector['color'];

            foreach ($arrAnos as $ano) {
                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                $arrValoresCrudo = $this->entityManager->getRepository(Emission::class)
                    ->findSectorGroupedByYear();

                if (empty($arrValoresCrudo)) {
                    $this->response->setStatusCode(404);
                }
                // HASTA ACA

                $response['column_'.$column][] = Utils::returnSectorAno($arrValoresCrudo, $sector['name'], $ano);
            }

            $column++;
        }


        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;

        return new JsonModel($response);
    }

    public function getSectoralEvolutionAction()
    {
        $sector = (int) $this->params()->fromRoute('sector');

        $response = [];

        // TRAIGO LOS SECTORES CON SUS COLORES

        $arrSectores = $this->entityManager->getRepository(Sector::class)->getSector($sector);

        if (empty($arrSectores)) {
            $this->response->setStatusCode(404);
        }

        // TRAIGO LOS VALORES POR ANO
        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA

        $arrAnos = [];
        $arrValores = [];
        $arrColores = [];

        for ($i = self::START_YEAR; $i <= self::END_YEAR; $i++) {
            $arrAnos[] = $i;
        }

        $column = 2;

        foreach ($arrSectores as $sector) {
            $response['column_'.$column][] = $sector['name'];
            $response['colores'][] = $sector['color'];

            foreach ($arrAnos as $ano) {
                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                $arrValoresCrudo = $this->entityManager->getRepository(Emission::class)
                    ->findSectorGroupedByYear();

                if (empty($arrValoresCrudo)) {
                    $this->response->setStatusCode(404);
                }
                // HASTA ACA

                $response['column_'.$column][] = Utils::returnSectorAno($arrValoresCrudo, $sector['name'], $ano);
            }

            $column++;
        }

        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;

        return new JsonModel($response);
    }

    public function getSectoralEvolutionSubactivityAction()
    {
        $sector = (int) $this->params()->fromRoute('sector');

        $response = [];

        $arrSubactividades = $this->entityManager->getRepository(Subactivity::class)
            ->findActivitySectorBySector($sector);

        if (empty($arrSubactividades)) {
            $this->response->setStatusCode(404);
        }

        // TRAIGO LOS VALORES POR ANO
        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA

        $arrAnos = [];
        $arrValores = [];
        $arrColores = [];

        for ($i = self::START_YEAR; $i <= self::END_YEAR; $i++) {
            $arrAnos[] = $i;
        }

        $column = 2;

        foreach ($arrSubactividades as $subactividad) {
            $response['column_'.$column][] = $subactividad['name'];
            $response['groups'][] = $subactividad['name'];

            foreach ($arrAnos as $ano) {
                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                $arrValoresCrudo = $this->entityManager->getRepository(Emission::class)
                    ->findSubactivitySectorBySector($sector);

                if (empty($arrValoresCrudo)) {
                    $this->response->setStatusCode(404);
                }
                // HASTA ACA

                $response['column_'.$column][] = Utils::returnSectorAno($arrValoresCrudo, $subactividad['name'], $ano);
            }

            $column++;
        }

        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;

        return new JsonModel($response);
    }

    public function getSectoralEvolutionSubactivityCategoryAction()
    {
        $sector = (int) $this->params()->fromRoute('sector');
        $subactivity = (int) $this->params()->fromRoute('subactivity');

        $response = [];

        $arrCategorias = $this->entityManager->getRepository(Emission::class)
            ->findSubactivitySectorCategoryBySectorSubactivity($sector, $subactivity);

        $sql = "SELECT sub.nombre as subcategoria, e.ano, c.nombre, e.valor
                FROM emision e
                INNER JOIN subactividad sub ON (e.subactividad_id = sub.id)
                INNER JOIN sector s ON (e.sector_id = s.id) 
                INNER JOIN categoria c ON (e.categoria_id = c.id)
                WHERE 1
                AND s.id = ? 
                AND sub.id = ? 
                GROUP BY e.ano, c.nombre";

        // LO QUE ESTA ADENTRO DEL LOOP DEBERIA IR ACA
        $arrAnos = [];
        $arrValores = [];
        $arrColores = [];

        for ($i = self::START_YEAR; $i <= self::END_YEAR; $i++) {
            $arrAnos[] = $i;
        }

        $column = 2;

        // // // pr($arrCategorias);
        // // // pr($arr);

        foreach ($arrCategorias as $categoria) {
            $response['column_'.$column][] = $categoria['name'];
            $response['groups'][] = $categoria['name'];

            foreach ($arrAnos as $ano) {

                // ATENCION, CABECEADA
                // ESTOY EJECUTANDO EL QUERY CADA VEZ QUE NECESITO LA LISTA DE VALORES
                // ESTA PARTE DEBERIA AFUERA DEL LOOP Y SE DEBERIA REUTILIZAR $arrValoresCrudo
                $arrValoresCrudo = $this->entityManager->getRepository(Emission::class)
                    ->findSubactivitySectorCategoryBySectorSubactivityGroupByYearName($sector, $subactivity);
                
                if (empty($arrValoresCrudo)) {
                    //$this->response->setStatusCode(404);
                }
                // HASTA ACA

                $response['column_'.$column][] = Utils::returnCategoriaAno($arrValoresCrudo, $categoria['name'], $ano);
            }

            $column++;
        }

        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;
        
        return new JsonModel($response);
    }
}
