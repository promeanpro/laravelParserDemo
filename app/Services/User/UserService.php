<?php


namespace App\Services\User;


use App\DTO\UserDTO;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * @param UserDTO[] $userDTOList
     * @return User
     */
    public function createUserBatch(array $userDTOList): Collection
    {
        $datetime = new \DateTimeImmutable();

        $collection = new Collection();
        $cn = DB::connection(); //DB_CONNECTION из ENV
        $cn->beginTransaction();

        try {
            foreach ($userDTOList as $userDTO) {
                $userModel = new User();
                $userModel->setAttribute('id', $userDTO->getId());
                $userModel->setAttribute('first_name', $userDTO->getFirstName());
                $userModel->setAttribute('last_name', $userDTO->getLastName());
                $userModel->setAttribute('card', $userDTO->getCard());
                $userModel->setAttribute('email', $userDTO->getEmail());
                $userModel->setCreatedAt($datetime);
                $userModel->setUpdatedAt($datetime);
                $userModel->save();
                $collection->add($userModel);
            }

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $collection;
    }

    /**
     * @param UserDTO[] $userDTOList
     * @return User
     * @throws \Exception
     */
    public function updateUserBatch(array $userDTOList): Collection
    {
        $datetime = new \DateTimeImmutable();

        $collection = new Collection();

        $cn = DB::connection(); //DB_CONNECTION из ENV
        $cn->beginTransaction();

        try {
            foreach ($userDTOList as $userDTO) {
                $userModel = new User();
                $userModel->setAttribute('id', $userDTO->getId());
                $userModel->setAttribute('first_name', $userDTO->getFirstName());
                $userModel->setAttribute('last_name', $userDTO->getLastName());
                $userModel->setAttribute('card', $userDTO->getCard());
                $userModel->setAttribute('email', $userDTO->getEmail());
                $userModel->setUpdatedAt($datetime);
                $userModel->update();
                $collection->add($userModel);
            }

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return $collection;
    }


    /**
     * @stub
     * @param $datetime
     * @return Collection
     */
    public function whereUpdateAtLessThan($datetime, $limit, $offset): Collection
    {
        return new Collection();
    }

    /**
     * @stub
     * @param $idList
     * @return Collection
     */
    public function getByIdList($idList): Collection
    {
        return User::find($idList);
    }



}
