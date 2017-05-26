# Website Informe de Gases de Efecto Invernadero

## Introducción

Aplicación desarrollada con Zend Framework 3 y PHP 7.1.5.

El proyecto consta con 2 modulos:

- Application: 2 paginas HTML, home del proyecto y pagina de resultados de los informe de gases de efecto invernadero.
- Api: endpoints consultados por la pagina de resultados que proveen de data para los graficos de la pagina de resultados.

## Instalación

Clonar el proyecto y cambiar a la rama de desarrollo zf3-version.

```bash
$ git clone git@github.com:diegoangel/informe-gei.git
$ cd informe-gei
$ git checkout zf3-version
```

Una vez clonado el proyecto se puede testear inmediatamente utilizando el servidor embebido de PHP:

```bash
$ php -S 0.0.0.0:8080 -t public/ public/index.php
# O utilizar el alias definido en la sección scripts de composer.json:
$ composer serve
```

Esto iniciará el servidor de consola en el puerto 8080 y podrá navegarlo en http://localhost:8080/

**Nota:** El servidor embebido de PHP *es solo para desarrollo*.

## Modo desarrollo

El proyecto viene con un modo de desarrollo por defecto, util para declara configuracion que solo se ejecutara en modo desarrollo, en entorno local por ejemplo, y provee tres alias para habilitar, deshabilitar y consultar el estado:

```bash
$ composer development-enable  # habilita el modo desarrollo
$ composer development-disable # deshabilita el modo desarrollo
$ composer development-status  # informa sobre si esta o no habilitado el modo desarrollo
```

You may provide development-only modules and bootstrap-level configuration in
`config/development.config.php.dist`, and development-only application
configuration in `config/autoload/development.local.php.dist`. Enabling
development mode will copy these files to versions removing the `.dist` suffix,
while disabling development mode will remove those copies.

Development mode is automatically enabled as part of the skeleton installation process. 
After making changes to one of the above-mentioned `.dist` configuration files you will
either need to disable then enable development mode for the changes to take effect,
or manually make matching updates to the `.dist`-less copies of those files.

## Ejecutando Test Unitarios

Hay dos *test suite* para ejecutar, una para el Modulo Api, y otra para el modulo Application.

```bash
$ ./vendor/bin/phpunit --testsuite Api
$ ./vendor/bin/phpunit --testsuite Application
```

Para generar el reporte de *test coverage* del modulo Api, el cual contiene la logica de negocio.

```bash
$ ./vendor/bin/phpunit --coverage-html module/Api/test/coverage --testsuite Api
```

If you need to make local modifications for the PHPUnit test setup, copy
`phpunit.xml.dist` to `phpunit.xml` and edit the new file; the latter has
precedence over the former when running tests, and is ignored by version
control. (If you want to make the modifications permanent, edit the
`phpunit.xml.dist` file.)

## Web server setup

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

```apache
<VirtualHost *:80>
    ServerName zfapp.localhost
    DocumentRoot /path/to/zfapp/public
    <Directory /path/to/zfapp/public>
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

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

```nginx
http {
    # ...
    include sites-enabled/*.conf;
}
```


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zfapp.localhost.conf`
it should look something like below:

```nginx
server {
    listen       80;
    server_name  zfapp.localhost;
    root         /path/to/zfapp/public;

    location / {
        index index.php;
        try_files $uri $uri/ @php;
    }

    location @php {
        # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_param  SCRIPT_FILENAME /path/to/zfapp/public/index.php;
        include fastcgi_params;
    }
}
```

Restart the nginx, now you should be ready to go!

## QA Tools

The skeleton does not come with any QA tooling by default, but does ship with
configuration for each of:

- [phpcs](https://github.com/squizlabs/php_codesniffer)
- [phpunit](https://phpunit.de)

Additionally, it comes with some basic tests for the shipped
`Application\Controller\IndexController`.

If you want to add these QA tools, execute the following:

```bash
$ composer require --dev phpunit/phpunit squizlabs/php_codesniffer zendframework/zend-test
```

We provide aliases for each of these tools in the Composer configuration:

```bash
# Run CS checks:
$ composer cs-check
# Fix CS errors:
$ composer cs-fix
# Run PHPUnit tests:
$ composer test
```
