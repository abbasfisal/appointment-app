<?php

namespace Application\Domain\Services;

use Application\Infrastructure\Persistence\Database\Mysql\Database;
use Exception;

class RequestHandler
{
    private $data;

    public function __construct()
    {
        $input_data = file_get_contents('php://input');
        $this->data = json_decode($input_data, true);
    }

    public function validated()
    {
        return $this->data;
    }

    /**
     * @throws Exception
     */
    public function validate($rules): void
    {
        foreach ($rules as $field => $fieldRules) {
            $this->validateField($field, $fieldRules, $this->data[$field]);
        }
    }

    /**
     * @throws Exception
     */
    private function validateField($field, $fieldRules, $value): void
    {

        foreach ($fieldRules as $rule) {

            $params = explode(':', $rule);
            $ruleName = array_shift($params);

            switch ($ruleName) {
                case 'required':
                    if (!isset($value) || empty($value)) {
                        throw new Exception("$field is required.", 422);
                    }
                    break;
                case 'string':
                    if (!is_string($value)) {
                        throw new Exception("$field must be a string.", 422);
                    }
                    break;
                case 'min':
                    $minLength = $params[0];
                    if (mb_strlen($value, 'utf-8') < $minLength) {
                        throw new Exception("$field must be at least $minLength characters.", 422);
                    }
                    break;
                case 'max':
                    $maxLength = $params[0];
                    if (mb_strlen($value, 'utf-8') > $maxLength) {
                        throw new Exception("$field must not be greater than $maxLength characters.", 422);
                    }
                    break;
                case 'exists':
                    $params = explode(',', $params[0]);
                    $this->validateExists($field, $params, $value);
                    break;

                case 'date':
                    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $value)) {
                        throw new Exception("Date format should be Y-m-d.", 422);
                    }

                    $today = date('Y-m-d');
                    if ($value < $today) {
                        throw new Exception("Date must be today or in the future.", 422);
                    }
                    break;

                default:
                    throw new Exception("Unsupported validation rule: $ruleName.", 500);
                    break;
            }
        }
    }

    /**
     * @throws Exception
     */
    private function validateExists($field, $params, $value): void
    {
        if (count($params) < 2) {
            throw new Exception("Invalid exists rule format for $field.", 500);
        }

        $table = $params[0];
        $column = $params[1];


        $exists = $this->checkRecordExists($table, $column, $value);
        if (!$exists) {
            throw new Exception("$field does not exist in $table.", 422);
        }
    }

    private function checkRecordExists($table, $column, $value): bool
    {
        $db = Database::getInstance()->get();

        $query = "SELECT COUNT(*) FROM $table WHERE $column = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$value]);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }
}

