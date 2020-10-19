<?php


namespace App\Services\User\Import;


use App\Assemblers\UserImportToUserModelAssembler;
use App\DTO\UserDTO;
use App\Models\User;
use App\Services\User\UserService;

class UserImportService
{
    private $userImportReportService;
    private $userImportToUserModelAssembler;
    private $userService;
    private $userImportReportDTOAssembler;

    public function __construct(UserService $userService,
                                UserImportReportService $userImportReportService,
                                UserImportToUserModelAssembler $userImportToUserModelAssembler)
    {
        $this->userService = $userService;
        $this->userImportReportService = $userImportReportService;
        $this->userImportToUserModelAssembler = $userImportToUserModelAssembler;
    }

    private const BATCH_SIZE = 2;
    private const GET_OLD_USERS_BATCH_SIZE = 100;

    public function doSync($strategy, $input)
    {
        //Identifier,Name,Last name,Card ,email
        //111,Name1,Last1,444444,name1@test.com
        //222,Name2,Last2,555555,name2@test.com
        $maxCount = 10;
        $inputArray = [];

        for ($i = 1; $i < $maxCount; $i++) {
            $inputArray[] = ['Identifier' => $i,
                'Name' => 'Name' . $i,
                'Last name' => 'Last' . $i,
                'Card ' => md5($i),
                'email' => md5($i) . '@' . $i . '.com'];
        }

        $currentArray = [];
        $limit = self::BATCH_SIZE;
        $offset = 0;

        /** @var UserDTO[] $userDTOArray */
        $userDTOArray = [];
        // assemble dto's
        foreach ($inputArray as $row) {
            $userDTOArray[] = $this->userImportToUserModelAssembler->createUserDTO($row);
        }

        $updateTimestamp = new \DateTimeImmutable();
        for ($i = 0; $i < count($userDTOArray); $i++) {
            $currentArray[$userDTOArray[$i]->getId()] = $userDTOArray[$i];

            // got enough rows to process
            if (count($currentArray) == $limit || $i == count($userDTOArray) - 1) {
                $offset += $limit;
                $idList = array_keys($currentArray);

                // get users for current batch
                $users = $this->getUsersByIds($idList);

                $existedUsers = array_intersect_key($currentArray, $users);
                $nonExistedUsers = array_diff_key($currentArray, $users);

                //• Any new entry - need to be added
                $this->createUsers($nonExistedUsers);
                //• Any existing entry - should be updated
                //• Any entry that is currently deleted in the db, need to be restored.
                $this->updateUsers($existedUsers, $users);
                $currentArray = [];
            }
        }

        //• All the entries that are no longer in the file, but exist in db need to be removed (soft delete)
        $this->deleteUserUpdateEarlyThan($updateTimestamp);

        // print report
        $this->userImportReportService->printReport();
    }

    /**
     * return all users with deleted, key=user_id
     * @param $idList
     * @return array
     */
    private function getUsersByIds($idList): array
    {
        $userList = [];
        $users = $this->userService->getByIdList($idList);
        /** @var User $user */
        foreach ($users as $user) {
            $userList[$user->getAttribute('id')] = $user;
        }

        return $userList;
    }


    /**
     * @param UserDTO[] $userDTOList
     */
    private function createUsers($userDTOList)
    {
        $this->userService->createUserBatch($userDTOList);

        $createdUserList = [];
        foreach ($userDTOList as $userDTO) {
            $createdUserList[] = ['user_id' => $userDTO->getId(), 'old_values' => null, 'new_values' => $userDTO];
        }
        $this->userImportReportService->usersCreated($userDTOList);
    }

    /**
     * Update users
     * @param $userDTOList
     * @param $users
     */
    private function updateUsers($userDTOList, $users)
    {
        $this->userService->updateUserBatch($userDTOList);
        $restoredUserList = [];
        $updatedUserList = [];

        foreach ($userDTOList as $userDTO) {
            if (isset($users[$userDTO->getId()])) {
                $updatedUserList[] = ['user_id' => $userDTO->getId(), 'old_values' => $users[$userDTO->getId()]->getAttributes(), 'new_values' => $userDTO->toArray()];
            } else {
                $restoredUserList[] = ['user_id' => $userDTO->getId(), 'old_values' => $users[$userDTO->getId()]->getAttributes(), 'new_values' => $userDTO->toArray()];
            }
        }

        $this->userImportReportService->usersUpdated($updatedUserList);
        $this->userImportReportService->usersRestored($restoredUserList);
    }

    /**
     * Delete all users which was not update in last sync
     * @param $updateTimestamp
     */
    private function deleteUserUpdateEarlyThan($updateTimestamp)
    {
        $offset = 0;

        while (true) {
            $oldUsers = $this->userService->whereUpdateAtLessThan($updateTimestamp, self::GET_OLD_USERS_BATCH_SIZE, $offset);

            if ($oldUsers->count() === 0) {
                break;
            }

            $oldUsers->toArray();

            $this->userImportReportService->usersRemoved($oldUsers);
            $offset += self::GET_OLD_USERS_BATCH_SIZE;
        }
    }

//
//The parser needs to do validation (correct data format, no duplicates ....)
//In case some row cannot be parsed - the file is rejected.
//In case some row fails in validation - reject only this row.
//In case there is a duplicate, we reject both.
//In any case, we do not fail parsing because of some rejected row.
//
//The data received should have a mapping for each column, since it might be received in one format, but stored in another or we can take data from multiple columns and combine it into one that will be stored eventually.

}
