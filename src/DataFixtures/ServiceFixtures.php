<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$service, $description, $price])
        {
            $user = new Service();
            $user->setService($service);
            $user->setDescription($description);
            $user->setPrice($price);

            $manager->persist($user);
        }
        $manager->flush();
        $this->loadServicesRelation($manager);
    }

    public function loadServicesRelation($manager)
    {
        foreach($this->ServicesData() as [$service_id, $employee_id])
        {
            $employee = $manager->getRepository(Employee::class)->find($employee_id);
            $service = $manager->getRepository(Service::class)->find($service_id);

            $employee->addService($service);
            $manager->persist($employee);
        }

        $manager->flush();

    }

    private function getUserData(): array
    {
        return [
            ['Haircut', "is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
                4],
            ['Hair Styling', "to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.",
                5],
            ['Manicure', "belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old",
                3],
            ['Pedicure', "words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.",
                2],
            ['Some Work', " If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks",
                5],
        ];
    }

    private function ServicesData(): array
    {
        return [
            [1,1],
            [5,2],
            [3,3],
            [1,4],

            [2,1],
            [4,2],

            [3,2],
            [1,3],
            [2,4],

            [4,1],
            [5,5]
        ];
    }
}
