<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
//        foreach($this->CommentData() as [$content, $user, $employee, $created_at])
//        {
//            $comment = new Comment();
//
//            $user = $manager->getRepository(User::class)->find($user);
//            $employee = $manager->getRepository(Employee::class)->find($employee);
//
//            $comment->setContent($content);
//            $comment->setUserComment($user);
//            $comment->setEmployee($employee);
//            $comment->setCreatedAtForFixtures(new \DateTime($created_at));
//
//            dd($comment);
//
//            $manager->persist($comment);
//        }
//
//        $manager->flush();
    }

    private function CommentData()
    {
        return [
            ["five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets",
                1,1,"2018-10-08 12:34:45"],
            ["it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words",
                2,2,"2018-09-08 10:34:45"],
            ["letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.",
                3,3,"2018-08-08 23:34:45"],

            ["If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.",
                1,4,"2018-10-08 11:23:34"],
            ["Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc",
                2,5,"2018-09-08 15:17:06"],
            ["There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.",
                3,2,"2018-08-08 21:34:45"],
            ["to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.",
                1,4,"2018-08-08 22:34:45"],
            ["Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.",
                2,1,"2018-08-08 23:34:45"]
        ];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
