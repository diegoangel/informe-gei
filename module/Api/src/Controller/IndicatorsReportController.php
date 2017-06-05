<?php

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Api\Entity\Indicator;
use Api\Entity\IndicatorValue;

/**
 *
 */
class IndicatorsReportController extends AbstractRestfulController
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
    public function getIndicatorAction()
    {
        $indicator = (int) $this->params()->fromRoute('indicator');

        $response = [];

        // DATOS GENERALES DEL INDICADOR
        $arrIndicador = $this->entityManager->getRepository(Indicator::class)
            ->getIndicator($indicator);

        foreach ($arrIndicador as $indicador) {
            $response['indicador'] = $indicador;
            break;
        }

        $arrValores = $this->entityManager->getRepository(IndicatorValue::class)
            ->getIndicatorValue($indicator);

        $arrAnos = [];
        $arrValor = [];

        foreach ($arrValores as $a) {
            // SI EL NOMBRE TIENE UNA COMA LO TENGO QUE PONER ENTRE COMILLAS
            $arrAnos[]  = $a['year'];
            $arrValor[] = $a['value'];
        }

        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;

        $arrValor = array_merge(array($indicador['name']), $arrValor);
        $response['column_2'] = $arrValor;

        $response['unidad'] = $indicador['unit'];
        $response['descripcion'] = nl2br($indicador['description']);

        $response['colores'] = "#8064a2";

        return new JsonModel($response);
    }
}
