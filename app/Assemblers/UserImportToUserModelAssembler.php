<?php


namespace App\Assemblers;

use App\DTO\UserDTO;

class UserImportToUserModelAssembler
{
    public function createUserDTO($data): UserDTO
    {
        $dto = new UserDTO();
        $dto->setId($data['Identifier']);
        $dto->setFirstName($data['Name']);
        $dto->setLastName($data['Last name']);
        $dto->setCard($data['Card ']);
        $dto->setEmail($data['email']);

        return $dto;
    }
}
