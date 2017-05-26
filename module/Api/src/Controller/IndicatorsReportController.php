<?php

namespace Api\Controller;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Api\Helper\Utils;

/**
 *
 */
class IndicatorsReportController extends AbstractRestfulController
{
    /**
     *
     */
    private $db;

    public function __construct(AdapterInterface $db)
    {
        $this->db = $db;
    }

    public function getIndicatorAction()
    {
        $params = $this->params()->fromRoute();

        $indicador_id = (int)$params['indicador_id'];

        $response = [];

        // DATOS GENERALES DEL INDICADOR

        $sql = "SELECT * FROM indicador WHERE id = ?";

        $params = [
           
            $indicador_id
        ];

        $statement = $this->db->createStatement($sql, $params);
        $arrIndicador = $statement->execute();

        while ($indicador = $arrIndicador->next()) {
            $response['indicador'] = $indicador;
            break;
        }


        $sql = "SELECT * FROM indicador_valor 
                WHERE indicador_id = ?
                ORDER BY ano ASC";

        $params = [
           
            $indicador_id
        ];

        $statement = $this->db->createStatement($sql, $params);
        $arrValores = $statement->execute();

        $arrAnos = [];
        $arrValor = [];

        while ($a = $arrValores->next()) {
            // SI EL NOMBRE TIENE UNA COMA LO TENGO QUE PONER ENTRE COMILLAS
            $arrAnos[]  = $a['ano'];
            $arrValor[] = $a['valor'];
        }

        $arrAnos = array_merge(array('x'), $arrAnos);
        $response['column_1'] = $arrAnos;

        $arrValor = array_merge(array($indicador['nombre']), $arrValor);
        $response['column_2'] = $arrValor;

        $response['unidad'] = $indicador['unidad'];
        $response['descripcion'] = nl2br($indicador['descripcion']);

        $response['colores'] = "#8064a2";

        return new JsonModel($response);
    }
}
