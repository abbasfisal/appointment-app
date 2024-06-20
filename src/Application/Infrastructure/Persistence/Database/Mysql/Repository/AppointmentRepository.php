<?php

namespace Application\Infrastructure\Persistence\Database\Mysql\Repository;

use Application\Domain\Repositories\AppointmentRepositoryInterface;
use Exception;
use PDO;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function getAll()
    {
        $query = "
        SELECT
            appointments.id AS appointment_id,
            appointments.date as appointment_date,
            users.name AS user_name,
            users.id AS user_id,
            time_slots.start_time,
            time_slots.end_time
        FROM
            appointments
        JOIN
            users ON appointments.user_id = users.id
        JOIN
            time_slots ON appointments.time_slot_id = time_slots.id
        ORDER BY appointment_date DESC
    ";

        $stm = $this->pdo->prepare($query);
        $stm->execute();

        return $stm->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @throws Exception
     */
    public function delete(array $data)
    {
        $user_id = $data['user_id'];
        $appointment_id = $data['appointment_id'];


        $query = "DELETE FROM appointments WHERE id = :appointment_id AND user_id = :user_id";


        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            return "Appointment with ID $appointment_id deleted successfully.";
        } else {
            throw new Exception("Appointment with ID $appointment_id not found for user $user_id.", 404);
        }
    }

    /**
     * @throws Exception
     */
    public function create(array $data)
    {
        $user_id = $data['user_id'];
        $time_slot_id = $data['time_slot_id'];
        $date = $data['date'];

        $query = "SELECT COUNT(*) AS count FROM appointments WHERE time_slot_id = :time_slot_id AND date = :date";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':time_slot_id', $time_slot_id, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            throw new Exception("Appointment slot is already reserved for the selected date and time slot.", 409);
        }

        $insertQuery = "INSERT INTO appointments (user_id, time_slot_id, date) VALUES (:user_id, :time_slot_id, :date)";
        $insertStmt = $this->pdo->prepare($insertQuery);
        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':time_slot_id', $time_slot_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':date', $date, PDO::PARAM_STR);
        $insertStmt->execute();

        return "Appointment created successfully.";

    }

}