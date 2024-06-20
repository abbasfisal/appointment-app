<?php

namespace Application\Infrastructure\Presentation\Web\Controllers;

use Application\Domain\Services\AppointmentService;
use Application\Domain\Services\RequestHandler;
use src\utils\Response;

/**
 * @OA\Info(title="Appointment Web Application "  , version="1.0")
 */
class AppointmentController
{
    private RequestHandler $request;

    public function __construct(private AppointmentService $appointmentService)
    {
        $this->request = new RequestHandler();
    }

    /**
     * @OA\Get(
     *     path="/appointments",
     *     tags={"Appointments"},
     *     summary="get appointments list ",
     *     description="get appointments list ",
     *     @OA\Response(response="200", description="An example resource")
     * )
     */
    public function index(): void
    {
        $data = $this->appointmentService->getAppointmentList();
        Response::json($data, message: 'appointments list');
    }

    /**
     * @OA\Post(
     *     path="/appointments/create",
     *     tags={"Appointments"},
     *     summary="Create a new appointment",
     *     description="Create a new appointment with user ID, time slot ID, and date.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "time_slot_id", "date"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="time_slot_id", type="integer", example=1),
     *             @OA\Property(property="date", type="string", format="date", example="2024-06-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Appointment created successfully"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad request, missing required fields or invalid data"
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/appointments/cancel",
     *     tags={"Appointments"},
     *     summary="Delete an appointment",
     *     description="Delete an appointment by user ID and appointment ID.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "appointment_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="appointment_id", type="integer", example=100)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Appointment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Appointment not found"
     *     )
     * )
     */
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