<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApiTest\Controller;

use PDO;
use Api\Controller\IndicatorsReportController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use Zend\Json\Json;

class IndicatorsReportControllerTest extends AbstractHttpControllerTestCase
{
    use TestCaseTrait;

    private static $pdo = null;

    private $conn = null;

    public function setUp()
    {
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));

        $services = $this->getApplicationServiceLocator();
        $config = $services->get('config');
        unset($config['db']);
        $services->setAllowOverride(true);
        $services->setService('config', $config);
        $services->setAllowOverride(false);

        parent::setUp();
    }

    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO('sqlite::memory:');
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, ':memory:');
        }

        return $this->conn;
    }

    protected function getDataSet()
    {
        return $this->createXMLDataSet(__DIR__ . '/../fixtures/database.xml');
    }

    public function testGetIndicatorActionCanBeAccessed()
    {
        $this->dispatch('/informe/indicador/2', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertResponseHeaderContains('Content-Type', 'application/json; charset=utf-8');
        $this->assertModuleName('api');
        $this->assertControllerName(IndicatorsReportController::class);
        $this->assertControllerClass('IndicatorsReportController');
        $this->assertMatchedRouteName('informe-indicador');

        $data = $this->getResponse()->getContent();

        $this->assertJson($data);

        $data = Json::decode($data, Json::TYPE_ARRAY);

        $this->assertArrayHasKey('column_1', $data);
        $this->assertArrayHasKey('colores', $data);
        $this->assertArrayHasKey('column_2', $data);
        $this->assertArrayHasKey('descripcion', $data);
        $this->assertArrayHasKey('unidad', $data);
        $this->assertArrayHasKey('indicador', $data);                        
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
