<?php

namespace Api\Factory;

use Zend\Db\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\DistributionReportController;

/**
 * Factory class for creation of Distribution Report Controller instances 
 */
class DistributionReportControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new DistributionReportController(
        	$container->get(AdapterInterface::class)
        );
    }
}