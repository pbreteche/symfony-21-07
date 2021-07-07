<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
    * @return Article[] Returns an array of Article objects
    */
    public function findByMonth(\DateTimeImmutable $month)
    {
        return $this->createQueryBuilder('article')
            ->andWhere('article.publishedAt >= :first_day')
            ->andWhere('article.publishedAt < :last_day')
            ->getQuery()
            ->setParameters([
                'first_day' => $month->modify('first day of this month midnight'),
                'last_day' => $month->modify('first day of next month midnight'),
            ])->getResult();
    }

    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findByMonth2(\DateTimeImmutable $month)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT a, author FROM '.Article::class.' a'.
            ' INNER JOIN a.writtenBy author'.
            ' WHERE a.publishedAt >= :first_day AND a.publishedAt < :last_day'
        )
            ->setParameters([
                'first_day' => $month->modify('first day of this month midnight'),
                'last_day' => $month->modify('first day of next month midnight'),
            ])->getResult();
    }

    protected function createMyQueryBuilder(string $alias)
    {
        return $this->createQueryBuilder($alias)
            ->join($alias.'.writtenBy', 'author')
            ->addOrderBy($alias.'.publishedAt', 'DESC')
            ->addSelect('author');
    }
}
