<?php
require_once __DIR__ . '/../../../../../vendor/autoload.php';

use Application\Domain\Repositories\AppointmentRepositoryInterface;
use Application\Domain\Services\AppointmentService;
use Application\Infrastructure\Presentation\Router;
use Application\Infrastructure\Presentation\Web\Controllers\AppointmentController;
use Bootstrap\App;
use src\utils\ErrorHandler;

set_exception_handler([ErrorHandler::class, 'handle']);


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../../../');
$dotenv->safeLoad();


$app = new App();
$router = new Router();

$appointmentService = new AppointmentService($app->get(AppointmentRepositoryInterface::class));
$appointmentController = new AppointmentController($appointmentService);


//routes

$router->get('/appointments', [$appointmentController, 'index']);
$router->post('/appointments/create', [$appointmentController, 'create']);
$router->patch('/appointments/cancel', [$appointmentController, 'cancel']);


$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


