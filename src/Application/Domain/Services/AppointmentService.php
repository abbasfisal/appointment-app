<?php

namespace Application\Domain\Services;

use Application\Domain\Repositories\AppointmentRepositoryInterface;

class AppointmentService
{
    public function __construct(private AppointmentRepositoryInterface $repository)
    {
    }


    public function getAppointmentList()
    {
        return $this->repository->getAll();
    }

    public function cancelAppointment(array $data)
    {
        return $this->repository->delete($data);
    }

    public function createAppointment(array $data)
    {
        return $this->repository->create($data);
    }
}