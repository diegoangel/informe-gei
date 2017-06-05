<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the cyearnical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
        // Distribution Report Routes
            'informe-todos-sectores' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-sectores/:year',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getWholeSectoralDistribution',
                    ],
                ],
            ],
            'informe-por-sector' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-sector/:year/:sector',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getSectoralDistribution',
                    ],
                ],
            ],
            'informe-gas' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-gases/:year',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getGasesDistribution',
                    ],
                ],
            ],
            'informe-gas-por-sector' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-gases-sector/:year',
                    'defaults' => [
                        'controller' => Controller\DistributionReportController::class,
                        'action'     => 'getSectoralGasesDistribution',
                    ],
                ],
            ],
            // Evolution Report Routes
            'informe-evolucion-sectores' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/evolucion-sectores',
                    'defaults' => [
                        'controller' => Controller\EvolutionReportController::class,
                        'action'     => 'getWholeSectoralEvolution',
                    ],
                ],
            ],
            'informe-evolucion-sector' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/evolucion-sector/:sector',
                    'defaults' => [
                        'controller' => Controller\EvolutionReportController::class,
                        'action'     => 'getSectoralEvolution',
                    ],
                ],
            ],
            'informe-evolucion-sector-subactividad' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/evolucion-sector-subactividad/:sector',
                    'defaults' => [
                        'controller' => Controller\EvolutionReportController::class,
                        'action'     => 'getSectoralEvolutionSubactivity',
                    ],
                ],
            ],
            'informe-evolucion-sector-subactividad-categoria' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/evolucion-sector-subactividad-categoria/:sector/:subactivity',
                    'defaults' => [
                        'controller' => Controller\EvolutionReportController::class,
                        'action'     => 'getSectoralEvolutionSubactivityCategory',
                    ],
                ],
            ],
            // Indicators Report Routes
            'informe-indicador' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/indicador/:indicator',
                    'defaults' => [
                        'controller' => Controller\IndicatorsReportController::class,
                        'action'     => 'getIndicator',
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
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
