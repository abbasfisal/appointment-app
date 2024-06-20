<?php

namespace Application\Infrastructure\Presentation\Web\Controllers;

use Application\Domain\Services\AppointmentService;
use Application\Domain\Services\RequestHandler;
use src\utils\Response;

class AppointmentController
{
    private RequestHandler $request;

    public function __construct(private  AppointmentService $appointmentService)
    {
        $this->request = new RequestHandler();
    }


    public function index(): void
    {
        $data = $this->appointmentService->getAppointmentList();
        Response::json($data, message: 'appointments list');
    }

    public function create(): void
    {
        $this->request->validate([
            'user_id'      => ['required', 'exists:users,id'],
            'time_slot_id' => ['required', 'exists:time_slots,id'],
            'date'         => ['required', 'date']
        ]);

        $this->appointmentService->createAppointment($this->request->validated());
        Response::json([], 201, 'success create appointment');
    }

    public function cancel(): void
    {
        $this->request->validate([
            'user_id'        => ['exists:users,id'],
            'appointment_id' => ['exists:appointments,id']
        ]);

        $this->appointmentService->cancelAppointment($this->request->validated());
        Response::json([], 200, 'successfully removed appointment');

    }
}