<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace ApiTest\Controller;

use PDO;
use Api\Controller\DistributionReportController;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use PHPUnit\DbUnit\TestCaseTrait;
use GuzzleHttp\Client as HttpClient;

class DistributionReportControllerTest extends AbstractHttpControllerTestCase
{

    use TestCaseTrait;

    static private $pdo = null;

    private $conn = null;

    private $httpClient = null;

    private $baseUri = '0.0.0.0';

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

        $this->httpClient = new HttpClient([
            'base_uri' => $this->baseUri
        ]);

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

    public function testGetWholeSectoralDistributionActionActionCanBeAccessed()
    {
        $this->dispatch('/informe/distribucion-sectores/2014', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('api');
        $this->assertControllerName(DistributionReportController::class);
        $this->assertControllerClass('DistributionReportController');
        $this->assertMatchedRouteName('informe-todos-sectores');
    }

    public function testInvalidRouteDoesNotCrash()
    {
        $this->dispatch('/invalid/route', 'GET');
        $this->assertResponseStatusCode(404);
    }
}
