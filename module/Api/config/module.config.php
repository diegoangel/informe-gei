<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'informe-todos-sectores' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-sectores[/:ano]',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'informe-por-sector' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-sector[/:ano[/:sector_id]]',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getSectoralDistribution',
                    ],
                ],
            ],
            'informe-gas' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-gases[/:ano]',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getGasesDistribution',
                    ],
                ],
            ],
            'informe-gas-por-sector' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-gases-sector[/:ano]',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getSectoralGasesDistribution',
                    ],
                ],
            ],          
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\DistributionReportController::class => Factory\DistributionReportControllerFactory::class,
            Controller\EvolutionReportController::class => Factory\EvolutionReportControllerFactory::class,
             Controller\IndicatorsReportController::class => Factory\IndicatorsReportControllerFactory::class,     
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],        
    ],
];
