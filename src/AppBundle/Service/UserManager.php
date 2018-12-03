<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

class UserManager
{


    /**
     * @param array $birhDates
     * @return array
     * @throws \Exception
     */
    private function getUsersAgeByBirthDates(array $birhDates): array
    {
        $age = [];
        $currentDate = new \DateTime();
        foreach ($birhDates as $birth)
        {
            $age[] = $currentDate->diff(new \DateTime($birth))->y;
        }

        return $age;
    }


    /**
     * @param array $users
     * @return float|int
     * @throws \Exception
     */
    public function calculateUsersAverageAge(array $users)
    {
        $usersAge = $this->getUsersAgeByBirthDates($this->getUsersBirthDates($users));

        return array_sum($usersAge) / count($usersAge);
    }


    /**
     * @param User[] $users
     * @return array
     */
    public function getUsersBirthDates(array $users)
    {
        $birthDates = [];
        foreach ($users as $user)
        {
            $birthDates[] = $user->getBirthDate();
        }

        return $birthDates;
    }


}