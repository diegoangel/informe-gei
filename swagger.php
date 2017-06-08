<?php
require("vendor/autoload.php");
$swagger = \Swagger\scan('module/Api/src/Controller');
header('Content-Type: application/json');
echo $swagger;