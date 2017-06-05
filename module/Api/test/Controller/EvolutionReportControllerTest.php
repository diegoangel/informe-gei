<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApiTest\Controller;

use Api\Controller\EvolutionReportController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Json\Json;

class EvolutionReportControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }
    
    public function testGetWholeSectoralEvolutionActionCanBeAccessed()
    {
        $this->dispatch('/informe/evolucion-sectores', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(EvolutionReportController::class);
        $this->assertControllerClass('EvolutionReportController');
        $this->assertMatchedRouteName('informe-evolucion-sectores');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('column_1', $data);
        $this->assertArrayHasKey('colores', $data);
        $this->assertArrayHasKey('column_2', $data);
    }

    public function testGetSectoralEvolutionActionCanBeAccessed()
    {
        $this->dispatch('/informe/evolucion-sector/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(EvolutionReportController::class);
        $this->assertControllerClass('EvolutionReportController');
        $this->assertMatchedRouteName('informe-evolucion-sector');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('column_1', $data);
        $this->assertArrayHasKey('colores', $data);
        $this->assertArrayHasKey('column_2', $data);
    }

    public function testGetSectoralEvolutionSubactivityActionCanBeAccessed()
    {
        $this->dispatch('/informe/evolucion-sector-subactividad/2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(EvolutionReportController::class);
        $this->assertControllerClass('EvolutionReportController');
        $this->assertMatchedRouteName('informe-evolucion-sector-subactividad');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('column_1', $data);
        $this->assertArrayHasKey('groups', $data);
        $this->assertArrayHasKey('column_3', $data);
    }

    public function testGetSectoralEvolutionSubactivityCategoryActionCanBeAccessed()
    {
        $this->dispatch('/informe/evolucion-sector-subactividad-categoria/1/2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(EvolutionReportController::class);
        $this->assertControllerClass('EvolutionReportController');
        $this->assertMatchedRouteName('informe-evolucion-sector-subactividad-categoria');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }

    // public function testInvalidHttpVerbDoesNotCrash()
    // {
    //     $this->dispatch('/informe/distribucion-sectores/2014', 'DELETE');
    //     $this->assertResponseStatusCode(405);
    // }
}
