<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function findByRole(string $role)
    {
        $rsm = $this->createResultSetMappingBuilder('s');

        $rawQuery = sprintf(
            'SELECT %s
        FROM users s 
        WHERE s.roles::jsonb ?? :role',
            $rsm->generateSelectClause()
        );

        $query = $this->getEntityManager()->createNativeQuery($rawQuery, $rsm);
        $query->setParameter('role', $role);
        return $query->getResult();
    }


//    public function findFirstLastNameUsers($id): array
//    {
//        $choiceArray = [];
//
//        $users = $this->createQueryBuilder('u')
//            ->select('u.first_name, u.last_name')
//            ->andWhere('u.id = :id')
//            ->setParameter('id', $id)
//            ->getQuery()
//            ->getResult()
//            ;
//
//        foreach ($users as $user) {
//            $choiceArray[sprintf('%s %s', $user['first_name'], $user['last_name'])] = $user['id'];
//        }
//
//        return $choiceArray;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
