<?php

namespace Api\Factory;

use Zend\Db\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\IndicatorsReportController;

/**
 * Factory class for creation of Indicators Report Controller instances
 */
class IndicatorsReportControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new IndicatorsReportController(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}
