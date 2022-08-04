<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Employee>
 *
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Employee::class);
        $this->paginator = $paginator;
    }

    public function add(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Employee $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByRequest(string $query, int $page, ?string $sort_method)
    {
        $sort_method = 'ASC';

        $querybuilder = $this->createQueryBuilder('e');
        $searchTerms = $this->prepareQuery($query);

        foreach ($searchTerms as $key => $term)
        {
            $querybuilder
                ->join('e.services', 'es'.$key)
                ->orWhere('lower(e.first_name) LIKE lower(:e_'.$key. ')')
                ->orWhere('lower(e.last_name) LIKE lower(:e_'.$key. ')')
                ->orWhere('lower(es'.$key.'.service) LIKE lower(:e_'.$key. ')')
                ->setParameter('e_'.$key, '%'.trim($term).'%');
        }

        return $querybuilder
            ->orderBy('e.first_name', $sort_method)
            ->getQuery()
            ->getResult();
    }

    private function prepareQuery(string $query): array
    {
        $terms = array_unique(explode(' ', $query));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

//    /**
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     * @throws \Doctrine\ORM\NoResultException
//     */
//    public function findFirstLastNameUsers($id): string
//    {
//        $employee = $this->createQueryBuilder('e')
//            ->select('e.first_name, e.last_name')
//            ->andWhere('e.id = :id')
//            ->setParameter('id', $id)
//            ->getQuery()
//            ->getSingleResult()
//        ;
//
//        return sprintf('%s %s', $employee['first_name'], $employee['last_name']);
//
//    }

//    public function findOneBySomeField($value): ?Employee
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
