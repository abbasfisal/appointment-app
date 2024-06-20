<?php

namespace Application\Domain\Repositories;

interface AppointmentRepositoryInterface
{

    public function getAll();

    public function delete(array $data);

    public function create(array $data);
}