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

    public function getSectoralEvolutionAction()
    {
        $params = $this->params()->fromRoute();

        $ano = (int)$params['ano'];

        // TODO: insert remaining logic from ajax.php file      

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