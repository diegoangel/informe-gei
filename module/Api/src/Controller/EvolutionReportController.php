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

}