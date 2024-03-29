<?php

namespace App\Repository;

use App\Entity\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Card>
 *
 * @method Card|null find($id, $lockMode = null, $lockVersion = null)
 * @method Card|null findOneBy(array $criteria, array $orderBy = null)
 * @method Card[]    findAll()
 * @method Card[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, Card::class);
    }

    public function getAllUuids(): array
    {
        $result =  $this->createQueryBuilder('c')
            ->select('c.uuid')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY)
        ;
        return array_column($result, 'uuid');
    }

    /**
     * Find all cards with pagination
     * @param int $page The current page
     * @param int $limit The maximum number of items per page
     * @return array The list of cards
     */
    public function getAllWithPagination(int $page = 1, string $setCode = '', int $limit = 10): array
    {
        if ($setCode === '') {
            return $this->createQueryBuilder('c')
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult(AbstractQuery::HYDRATE_ARRAY);
        }
        return $this->createQueryBuilder('c')
            ->where('c.setCode = :setCode')
            ->setParameter('setCode', $setCode)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function search(string $search, string $setCode = ''): array
    {
        if ($setCode === '') {
            return $this->createQueryBuilder('c')
                ->where('c.name LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->setMaxResults(20)
                ->getQuery()
                ->getResult(AbstractQuery::HYDRATE_ARRAY);
        }
        return $this->createQueryBuilder('c')
            ->where('c.name LIKE :search')
            ->andWhere('c.setCode = :setCode')
            ->setParameter('search', $search)
            ->setParameter('setCode', $setCode)
            ->setMaxResults(20)
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY);
    }
}
