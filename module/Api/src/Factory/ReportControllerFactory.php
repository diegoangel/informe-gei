<?php

namespace Api\Factory;

use Zend\Db\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\ReportController;

class ReportControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new ReportController(
        	$container->get(AdapterInterface::class)
        );
    }
}