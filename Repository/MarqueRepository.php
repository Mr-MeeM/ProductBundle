<?php

namespace Systeo\ProductBundle\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * MarqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MarqueRepository extends \Doctrine\ORM\EntityRepository
{
    public function MyFindAll($data)
   {
       $queryBuilder = $this->createQueryBuilder('a');
       
       $this->searchName($queryBuilder,$data);
       
       $queryBuilder->addOrderBy('a.name', 'ASC');
       
       $query = $queryBuilder->getQuery();
       $results = $query->getResult();
       
       return $results;
   }
   
   
   
   /**
    * 
    * @param type $name
    */
   private function searchName(QueryBuilder $qb, $data)
   {
       if(isset($data['name']) && !empty($data['name'])){
           $qb->andWhere('a.name like :name  ')
              ->setParameter('name', '%'.$data['name'].'%');
       }
   }
}