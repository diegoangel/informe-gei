# Website Informe de Gases de Efecto Invernadero

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/diegoangel/informe-gei/badges/quality-score.png?b=zf3-version)](https://scrutinizer-ci.com/g/diegoangel/informe-gei/?branch=zf3-version)  [![Code Coverage](https://scrutinizer-ci.com/g/diegoangel/informe-gei/badges/coverage.png?b=zf3-version)](https://scrutinizer-ci.com/g/diegoangel/informe-gei/?branch=zf3-version)  [![Build Status](https://scrutinizer-ci.com/g/diegoangel/informe-gei/badges/build.png?b=zf3-version)](https://scrutinizer-ci.com/g/diegoangel/informe-gei/build-status/zf3-version)

## Introducción

Aplicación desarrollada con Zend Framework 3, Doctrine 2, MySQL 5.7 y PHP 7.1.5.

El proyecto consta con 2 modulos:

#### Modulo Application

Dos paginas HTML, home del proyecto y pagina de resultados de los informe de gases de efecto invernadero.

#### Modulo Api

Endpoints consultados por la pagina de resultados que proveen de datos para los graficos de la pagina de resultados.

## Instalación

### Clonar el proyecto

```bash
$ git clone git@github.com:diegoangel/informe-gei.git
$ cd informe-gei
$ git checkout zf3-version
$ composer install
```

### Crear base de datos

Cambiar credenciales de conexión si corresponde y ejecutar:

```bash
mysql -u root -proot -e "CREATE SCHEMA informe_gei"
```

## Configuración

#### Conexion a base de datos

Los datos de configuracion de la conexion deben colocarlo en el archivo config/autoload/local.php

```php
...
return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => '127.0.0.1',
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'informe_gei',
                    'charset'  => 'utf8',
                ]
            ],
        ],
    ],
];
```

Este archivo no existira, por lo cual deben crearlo, copiando, pegando y renombrando el archivo config/autoload/local.php.dist

```bash
cp config/autoload/local.php.dist config/autoload/local.php
```

Además, este archivo es ignorado en el repositorio de control de versión y por lo tanto las credenciales de conexión nuncan seran compartidas por accidente y permanecen seguras.

### Doctrine Migrations (popular base de datos)

Los parametros de configuración de las migration de Doctrine se encuentran en  el archivo config/autoload/global.php

```php
...
return [
    'doctrine' => [
        // migrations configuration
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => 'data/Migrations',
                'name'      => 'Doctrine Database Migrations',
                'namespace' => 'Migrations',
                'table'     => 'migrations',               
            ],
        ],
    ],
];
```

**==TODO ingresar comando para ejecutar las migrations y subir codigo de migration para que tenga sentido esta parte de la codumentacion==**

## Iniciar servidor de desarrollo

Esto iniciará el servidor de consola en el puerto 8080 y podrá navegarlo en http://localhost:8080/

**Nota:** El servidor embebido de PHP *es solo para desarrollo*.

```bash
$ php -S 0.0.0.0:8080 -t public/ public/index.php
# O utilizar el alias definido en la sección scripts de composer.json:
$ composer serve
```

## Modo desarrollo para entorno local

El proyecto viene con un modo de desarrollo por defecto, util para declara configuracion que solo se ejecutara en modo desarrollo, en entorno local por ejemplo, y provee tres alias para habilitar, deshabilitar y consultar el estado:

```bash
$ composer development-enable  # habilita el modo desarrollo
$ composer development-disable # deshabilita el modo desarrollo
$ composer development-status  # informa sobre si esta o no habilitado el modo desarrollo
```

## Ejecución de test unitarios

Hay dos *test suite* para ejecutar, una para el Modulo Api, y otra para el modulo Application.

```bash
$ ./vendor/bin/phpunit  -v --debug --testsuite Api
$ ./vendor/bin/phpunit  -v --debug --testsuite Application
```

Pueden ejecutar ambas suites con el comando:
```bash
$ ./vendor/bin/phpunit  -v --debug
```
### Test coverage

Para generar el reporte de test coverage del modulo Api, el cual contiene la logica de negocio.

```bash
$ ./vendor/bin/phpunit -v --debug --coverage-html data/coverage 
```

Si necesitan agregar modificaciones locales en la configuracion de PHPUnit, copiar `phpunit.xml.dist` a `phpunit.xml` y editar el nuevo archivo; el ultimo tiene precedencia sobre el primero cuando se ejecutan los test y es ignorado por el sistema de control de versiones.
 (Si se quiere editar permanentemente la configuracion esitar el archivo 
`phpunit.xml.dist`.)

## Integración Continua (CI)

Se utilizó [Scrutinizer CI](https://scrutinizer-ci.com)  para ejecutar:

- Test de Integración.
- Test Unitarios.
- Test Coverage.
- Static Code Analisis.
- Chequeo de vulnerabilidades.
- Detección automatica de Malas Prácticas y Bugs.
- Complejidad Ciclomatica.

La evaluación de la calidad del código, la cobertura de tests y el estado del ultimo build se pueden visualizar a traves de los badges que se encuentran debajo del título del documento.

## Configuración servidor web

### Configuracion virtual host en Apache

Ejemplo de configuracion en Apache

```apache
<VirtualHost *:80>
    ServerName informe-gei.local
    DocumentRoot /var/www/html/informe-gei/public
    
    <Directory /var/www/html/informe-gei/public>
        DirectoryIndex index.php
        AllowOverride All 
        Order allow,deny
        Allow from all
        <IfModule mod_authz_core.c>
            Require all granted
        </IfModule>
    </Directory>
</VirtualHost>

```

