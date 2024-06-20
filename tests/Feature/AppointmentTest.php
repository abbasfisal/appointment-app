<?php

use Application\Domain\Services\AppointmentService;
use Application\Domain\Services\RequestHandler;
use Application\Infrastructure\Presentation\Router;
use Application\Infrastructure\Presentation\Web\Controllers\AppointmentController;
use PHPUnit\Framework\TestCase;

class AppointmentTest extends TestCase
{

    public function testGetAppointmentsRoute()
    {
        // Create AppointmentService instance with mocked repository
        $service = Mockery::mock(AppointmentService::class);
        $service->shouldReceive('getAppointmentList')
            ->once()
            ->andReturn([
                ['id' => 1, 'date' => '2024-06-19', 'user_name' => 'John Doe', 'start_time' => '10:00', 'end_time' => '11:00'],
                ['id' => 2, 'date' => '2024-06-20', 'user_name' => 'Jane Smith', 'start_time' => '11:00', 'end_time' => '12:00']
            ]);


        // Create the controller with mocked service
        $controller = new AppointmentController($service);

        // Mocking the Router and Response
        $router = new Router();
        $router->get('/appointments', [$controller, 'index']);

        // Capture the output
        ob_start();
        $router->dispatch('GET', '/appointments');
        $output = ob_get_clean();

        $response = json_decode($output, true);

        // Assertions
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('appointments list', $response['message']);
        $this->assertNotEmpty($response['data']);

        // Verifying the mock expectations
        Mockery::close();
    }


    public function _testCancel()
    {
        // Mock AppointmentService
        $mockAppointmentService = Mockery::mock(AppointmentService::class);

        // Mock RequestHandler
        $mockRequestHandler = Mockery::mock(RequestHandler::class);
        // Assumed validated data for request
        $validatedData = [
            'user_id'        => 1,
            'appointment_id' => 10
        ];

        // Mock the behavior of RequestHandler
        $mockRequestHandler
            ->shouldReceive('validate')
            ->once()
            ->with([
                'user_id'        => ['exists:users,id'],
                'appointment_id' => ['exists:appointments,id']
            ])
            ->andReturnTrue();  // Assume validation passes

        // Mock the behavior of AppointmentService
        $mockAppointmentService
            ->shouldReceive('cancelAppointment')
            ->once()  // Expected once call
            ->with($validatedData)
            ->andReturn('successfully removed appointment');

        // Create instance of AppointmentController with mocks
        $controller = new AppointmentController($mockAppointmentService);

        // Inject mock RequestHandler
        $reflectionClass = new ReflectionClass(AppointmentController::class);
        $property = $reflectionClass->getProperty('request');
        $property->setAccessible(true);
        $property->setValue($controller, $mockRequestHandler);

        // Act
        // Call the cancel method of AppointmentController
        $controller->cancel();

        //-----
        // Create the controller with mocked service

        // Mocking the Router and Response
        $router = new Router();
        $router->put('/appointments/cancel', [$controller, 'cancel']);

        // Capture the output
        ob_start();
        $router->dispatch('PUT', '/appointments/cancel');
        $output = ob_get_clean();

        $response = json_decode($output, true);

        // Assertions
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('appointments list', $response['message']);
        $this->assertNotEmpty($response['data']);

        //------



        // Assert
        // Here you can add assertions if needed
        $this->expectOutputString('{"message":"successfully removed appointment"}');  // Assuming Response::json() outputs JSON

    }

}
