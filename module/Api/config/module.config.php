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
            'informe' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/informe',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'informe-por-sector' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/informe/distribucion-sector[/:ano[/:sector_id]]',
                    'defaults' => [
                        'controller' => Controller\ReportController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ReportController::class => InvokableFactory::class,
            Controller\ReportController::class => Factory\ReportControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy'
        ],        
    ],
];
