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

        while($a = $results->next())
        {
            $response['sector_'.$i][]      = $a['nombre'];
            $response['sector_'.$i][]      = $a['total'];
            $response['colores'][]         = $a['color'];
            $response['descripciones'][]   = $a['descripcion'];

            $i++;
        }

        return new JsonModel($response);
    }

    public function getReportBySectoralDistributionAction() 
    {
    	 
    }    
}
