<?php


namespace App\Services\User\Import;

/**
 * Report need to be generated as csv files for each type for the following:
 * • Rows where that failed validation, with reason/s of fail
 * • Rows that were added - we need to show for values that were added.
 * • Rows that were updated - we need to show for values that were changed previous and new value.
 * • Rows that were removed - we need to show all the values that were before removal
 * • Rows that were restored - we need to show for values that were changed previous and new value.
 *
 *
 *
 * Summary of the result need to be printed to log/console
 * • Status passed/failed
 * • How many new/deleted/restored/updated/rejected.
 * Class UserImportReportService
 * @package App\Services\User\Import
 */
class UserImportReportService
{
    public const FIELD_USER_ID = 'user_id';
    public const FIELD_OLD_VALUES = 'old_values';
    public const FIELD_NEW_VALUES = 'new_values';

    private $rowsValidationFailures = [];
    private $rowsAdded = [];
    private $rowsUpdate = [];
    private $rowsRestored = [];
    private $rowsRemoved = [];

    /**
     * @param $data
     */
    public function usersCreated($data)
    {
        $this->rowsAdded = array_merge($this->rowsAdded, $data);
    }

    /**
     * @param $data
     */
    public function usersUpdated($data)
    {
        $this->rowsUpdate = array_merge($this->rowsUpdate, $data);
    }

    /**
     * @stub
     * @param $data
     */
    public function usersRemoved($data)
    {
        $this->rowsRemoved = array_merge($this->rowsRemoved, $data);
    }

    /**
     * @stub
     * @param $data
     */
    public function usersRestored($data)
    {
        $this->rowsRestored = array_merge($this->rowsRestored, $data);
    }

    /**
     * @stub
     * @param $data
     */
    public function printReport()
    {
        print_r($this->makeOutput($this->rowsValidationFailures));
        print_r($this->makeOutput($this->rowsAdded));
        print_r($this->makeOutput($this->rowsUpdate));
        print_r($this->makeOutput($this->rowsRestored));
        print_r($this->makeOutput($this->rowsRemoved));
    }

    private function makeOutput($data): array
    {
        $result = [];
        foreach ($data as $row) {
            $result = ['id' => $row[self::FIELD_USER_ID],
                $row[self::FIELD_OLD_VALUES],
                $row[self::FIELD_NEW_VALUES]];
        }

        return $result;
    }


}
