<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * @return mixed
     */
    public function indexAction()
    {
        $this->layout()->setVariables([
            'bodyCssClass' => 'home',
            'contentWrapperClass' => 'home-content-wrapper'
        ]);

        return new ViewModel();
    }
}
