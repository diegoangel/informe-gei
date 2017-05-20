<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\Json\Json;

class IndexController extends AbstractRestfulController
{
    public function indexAction()
    {
        return new JsonModel([
            'books' => 'blablablabla',
        ]);
    }

    public function getReportBySectoralDistributionAction() 
    {
    	 
    }    
}
