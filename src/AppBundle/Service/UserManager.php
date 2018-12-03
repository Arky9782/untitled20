<?php

namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;

class UserManager
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function repository(): UserRepository
    {
        return $this->repository;
    }


    /**
     * @throws \Exception
     */
    public function getUsersAge(): array
    {
        $users = $this->repository->findAllUsersWithBirthDate();

        if (!$users) {
            throw new \Exception('Cant get users age, no users with filled birth date found');
        }

        $age = [];
        $currentDate = (new \DateTime())->format('Y - m - d');
        foreach ($users as $user)
        {
            $birthDate = $user->getBirthDate();

            $age = $currentDate - $birthDate;
        }

        return $age;
    }

    /**
     * @return float|int
     * @throws \Exception
     */
    public function calculateUsersAverageAge()
    {
        $usersAge = $this->getUsersAge();

        return array_sum($usersAge)/count($usersAge);
    }


}