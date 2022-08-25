<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $password_encoder;

    public function __construct(UserPasswordHasherInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$first_name, $last_name, $email, $password, $phone, $roles])
        {
            $user = new User();
            $user->setFirstName($first_name);
            $user->setLastName($last_name);
            $user->setEmail($email);
            $user->setPassword($this->password_encoder->hashPassword($user, $password));
            $user->setPhone($phone);
            $user->setRoles($roles);

            $manager->persist($user);
        }
        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            ['John', 'Wayne', 'jw@symf4.loc', 'pass', '+375295462359', ['ROLE_ADMIN']],
            ['John', 'Wayne2', 'jw2@symf4.loc', 'pass', '+375294793621', ['ROLE_ADMIN']],
            ['John', 'Doe', 'jd@symf4.loc', 'pass', '+375298975265', ['ROLE_USER']]
        ];
    }
}
