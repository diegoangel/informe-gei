<?php

namespace ApiTest\Helper;

use Api\Helper\Utils;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class UtilsTest extends AbstractHttpControllerTestCase
{
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

    public function testReturnSectorGas()
    {
    }

    public function testReturnSectorAno()
    {
    }

    public function testReturnSubactividadAno()
    {
    }

    public function testReturnCategoriaAno()
    {
    }
}
