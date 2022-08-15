<?php

namespace App\Repository;

use App\Entity\Booking;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function add(Booking $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Booking $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findByEmployeeId($start, $end, $filters): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.employee', 'e')
            ->leftJoin('b.service', 's')
            ->andWhere('b.beginAt BETWEEN :start and :end')
            ->andWhere('e.id = :id')
            ->setParameter('id', $filters['id'])
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult();
    }

    public function findByUserId($id): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.employee', 'e')
            ->leftJoin('b.service', 's')
            ->andWhere('b.appointer = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function findByRequestApi($request): array
    {
        $beginAt = new DateTime($request->get('begin_at'));

        return $this->createQueryBuilder('b')
            ->where('b.id = :id')
            ->orWhere('b.appointer = :user_id')
            ->orWhere('b.service = :service')
            ->orWhere('b.employee = :employee_id')
            ->orWhere('b.beginAt = :begin_at')
            ->setParameter('id', $request->get('booking_id'))
            ->setParameter('user_id', $request->get('user_id'))
            ->setParameter('service', $request->get('service_id'))
            ->setParameter('employee_id', $request->get('employee_id'))
            ->setParameter('begin_at', $beginAt->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
