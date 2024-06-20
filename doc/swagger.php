<?php
require_once __DIR__ . '/../vendor/autoload.php';


use OpenApi\Generator;

$openapi = Generator::scan([__DIR__ . '/../src/Application/Infrastructure/Presentation/Web/Controllers'], [
    'exclude' => ['vendor'],
]);

header('Content-Type: application/json');
echo $openapi->toJson();

