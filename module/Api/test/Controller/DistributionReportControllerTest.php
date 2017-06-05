<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApiTest\Controller;

use Api\Controller\DistributionReportController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Json\Json;

class DistributionReportControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__.'/../../../../config/application.config.php',
            $configOverrides
        ));

        parent::setUp();
    }

    public function testGetWholeSectoralDistributionActionActionCanBeAccessed()
    {
        $this->dispatch('/informe/distribucion-sectores/2014', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(DistributionReportController::class);
        $this->assertControllerClass('DistributionReportController');
        $this->assertMatchedRouteName('informe-todos-sectores');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('sector_1', $data);
        $this->assertArrayHasKey('colores', $data);
        $this->assertArrayHasKey('descripciones', $data);
    }

    public function testGetSectoralDistributionActionCanBeAccessed()
    {
        $this->dispatch('/informe/distribucion-sector/2014/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(DistributionReportController::class);
        $this->assertControllerClass('DistributionReportController');
        $this->assertMatchedRouteName('informe-por-sector');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('graph_data', $data);
        $this->assertArrayHasKey('sector', $data);
        $this->assertArrayHasKey('totalActividades', $data);
    }

    public function testGetGasesDistributionActionCanBeAccessed()
    {
        $this->dispatch('/informe/distribucion-gases/2014', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(DistributionReportController::class);
        $this->assertControllerClass('DistributionReportController');
        $this->assertMatchedRouteName('informe-gas');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('colores', $data);
        $this->assertArrayHasKey('gases', $data);
        $this->assertArrayHasKey('valores', $data);
    }

    public function testGetSectoralGasesDistributionActionCanBeAccessed()
    {
        $this->dispatch('/informe/distribucion-gases-sector/2014', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(DistributionReportController::class);
        $this->assertControllerClass('DistributionReportController');
        $this->assertMatchedRouteName('informe-gas-por-sector');

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
