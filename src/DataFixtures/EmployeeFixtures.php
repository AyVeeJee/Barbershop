<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getUserData() as [$first_name, $last_name, $email, $phone])
        {
            $user = new Employee();
            $user->setFirstName($first_name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPhone($phone);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['Paul', 'White', 'ph@symf5.loc', '+375295462359'],
            ['Kevin', 'Bell', 'kh@symf5.loc', '+375294793621'],
            ['Robert', 'Flores', 'rm@symf5.loc', '+375298975265'],
            ['Robert', 'Morris', 'rp@symf5.loc', '+375294585455'],
            ['James', 'King', 'jh@symf5.loc', '+375299845454'],
        ];
    }
}
