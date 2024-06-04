<?php

namespace App\Repository;

use App\Entity\EmployeeEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(private readonly ManagerRegistry $registry)
    {
        parent::__construct($registry, EmployeeEntity::class);
    }

    public function add(EmployeeEntity $entity)
    {
        $manager = $this->getEntityManager();
        $manager->persist($entity);
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }

    public function getEmployee(mixed $id): EmployeeEntity
    {
        $result = $this->findBy(['id' => $id]);

        if (count($result) > 0) {
            return $result[0];
        }
        return new EmployeeEntity();
    }

    public function findBySearchQuery(?string $search, ?string $orderBy = null, int $limit = 10, $offset = 0): array
    {
        $queryBuilder = $this->createQueryBuilder('e');

        if ($search) {
            $queryBuilder->where($queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('CONCAT(e.firstname, \' \', e.lastname)', ':search'),
                $queryBuilder->expr()->like('e.email', ':search')
            ))
                ->setParameter('search', '%' . $search . '%');
        }

        if ($orderBy) {
            $queryBuilder->orderBy('e.' . $orderBy);
        }

        $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getEmployeeCount($query): int
    {
        $queryBuilder = $this->createQueryBuilder('e');

        $queryBuilder->select('COUNT(e.id)');

        if ($query) {
            $queryBuilder->where($queryBuilder->expr()->orX(
                $queryBuilder->expr()->like('CONCAT(e.firstname, \' \', e.lastname)', ':search'),
                $queryBuilder->expr()->like('e.email', ':search')
            ))
                ->setParameter('search', '%' . $query . '%');
        }
        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}