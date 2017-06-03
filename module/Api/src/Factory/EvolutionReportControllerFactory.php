<?php

namespace Api\Factory;

use Zend\Db\Adapter\AdapterInterface;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\EvolutionReportController;

/**
 * Factory class for creation of Evolution Report Controller instances
 */
class EvolutionReportControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new EvolutionReportController(
            $container->get('doctrine.entitymanager.orm_default')
        );
    }
}
