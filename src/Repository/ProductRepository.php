<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\Query;
use App\Search\SearchProduct;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Find/search product by title/content
    public function findProductByName(string $query)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('p.name', ':query'),
                        $qb->expr()->like('p.price', ':query'),
                    ),
                    
                )
            )
            
            ->setParameter('query', '%' . $query . '%')

           
        
        ;
        return $qb
            ->getQuery()
            ->getResult();
    }


     /**
     * @param SearchProduct $search
     * @return Query
     */
    public function findAllBySearchFilter(SearchProduct $search): Query
    {

        $query =  $this->findAllQuery();

        if($search->getFilterByName()) {
            $query = $query->andWhere('p.name LIKE :name');
            $query->setParameter('name', '%' . $search->getFilterByName() . '%');
        }
  
        if($search->getFilterByCategory()) {
            $query = $query->andWhere('p.category = :category_id');
            $query->setParameter('category_id', $search->getFilterByCategory()->getId());
        }

        $query->addOrderBy('p.id', 'DESC');
        return $query->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    public function findAllQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
