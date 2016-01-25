<?php

namespace PP\PropositionBundle\Entity;
use PP\RequestBundle\Constant\Constants;

/**
 * PropositionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PropositionRepository extends \Doctrine\ORM\EntityRepository
{
    public function getReportedProposition(){
        $qb = $this->createQueryBuilder('p')                        
                        ->distinct(true)
                        ->leftJoin('p.author', 'pA')
                        ->addSelect('pA')
                        ->where('p.enabled = true')
                        ->andWhere('p.reportNb > 0')
        ;
        
        return $qb
               ->getQuery()
               ->getResult()
            ;  
    }
    
    public function getPropositionByUser($userId, $limit, $page){
        
        $qb = $this
                ->createQueryBuilder('p')
                ->leftJoin('p.author', 'pA')
                ->addSelect('pA')
                ->leftJoin('p.image', 'pI')
                ->addSelect('pI')                    
                ->where('pA.id = :userId AND p.enabled = true')
                ->setParameter('userId', $userId)
        ;                        
            
        $qb = $qb
          ->setFirstResult(($page-1) * $limit)
          ->setMaxResults($limit)
        ;      

        return $qb
              ->getQuery()
              ->getResult()
        ;                                                    
    }
    
    public function countPropositions($id){
        
            $qb = $this
                    ->createQueryBuilder('p')
                    ->select('COUNT(p.id)')
                    ->where('p.imageRequest = :id AND p.enabled = true')
                    ->setParameter('id', $id)
            ;                        
            
            return  $qb
                    ->getQuery()
                    ->getSingleScalarResult();                                                    
    }
    
    public function getPropositions($imageRequestId, $limit, $page, $displayMode = null, $userId = 0, $followingIds = null, $searchParam = null, $tagsParam = null, $categoriesParam = null, $concerningMeParam = false)
    {
        $qb = $this
              ->createQueryBuilder('p')
              ->leftJoin('p.image', 'i')
              ->addSelect('i')
              ->leftJoin('p.author', 'pA')
              ->addSelect('pA')              
              ->leftJoin("p.imageRequest", "ir")
              ->leftJoin('ir.tags', 't')
              ->leftJoin('ir.category', 'c')
              ->where('ir.enabled = true AND pA.enabled = true')
              ->distinct(true)
              ->andWhere('ir.enabled = true AND p.enabled = true')
        ;
        
        if($searchParam != null){
            $qb = $qb
                    ->where($qb->expr()->like('ir.title', ':title'))
                    ->setParameter('title', '%'.$searchParam.'%')
                    ->orWhere($qb->expr()->like('t.name', ':name'))
                    ->setParameter('name', '%'.$searchParam.'%')
                    ->orWhere($qb->expr()->like('c.name', ':cat'))
                    ->setParameter('cat', '%'.$searchParam.'%')
                    ->orWhere($qb->expr()->like('p.title', ':propTitle'))
                    ->setParameter('propTitle', '%'.$searchParam.'%');

        }

        if($tagsParam != null){
            $i = 0;
            foreach ($tagsParam as $tagName){
                $qb = $qb
                        ->andWhere($qb->expr()->like('t.id', ':nameT'.$i))
                        ->setParameter('nameT'.$i, $tagName);
                $i++;
            }                
        }

        if($categoriesParam != null){
            $i = 0;
            $request = '';
            foreach ($categoriesParam as $cat){
                if($i>0)$request .= ' OR ';
                $request .= 'c.id = :idC'.$i;
                $qb = $qb                            
                        ->setParameter('idC'.$i, $cat);
                $i++;
            }
             $qb = $qb
                        ->andWhere($request);
        }

        if($concerningMeParam){
            $qb = $qb
                    ->andWhere("pA.id = :userId2")
                    ->setParameter('userId2', $userId);
        }
        
        if($displayMode == Constants::ORDER_BY_DATE){
            $qb = $qb
                    ->orderBy('p.createdDate', 'DESC');
        }else if($displayMode == Constants::ORDER_BY_UPVOTE){
             $qb = $qb
                    ->orderBy('p.upvote', 'DESC');
        }else if($displayMode == Constants::ORDER_BY_INTEREST){
            $qb = $qb                                               
                ->from('PPUserBundle:User', 'u')                         
                ->andwhere('u.id = :userId')
                ->setParameter('userId', $userId)
                ->leftJoin('u.following', 'uF')                       
                ->andwhere("pA.id IN(:followingIds) AND pA.enabled = true")
                ->setParameter('followingIds', array_values($followingIds))
                ->orderBy('p.createdDate', 'DESC'); 
            ;
        }else{
            $qb = $qb
                    ->orderBy('p.createdDate', 'DESC');
        }
        
        if($imageRequestId!=null){
            $qb = $qb
                   ->andwhere("ir.id = :imageRequestId")
                   ->setParameter('imageRequestId', $imageRequestId);
        }
      
        $qb = $qb
              ->setFirstResult(($page-1) * $limit)
              ->setMaxResults($limit)
        ;      

      return $qb
            ->getQuery()
            ->getResult()
      ;
    }
    
    public function countOneUserPropositions($userId){
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.imageRequest', 'ir')
                ->select('COUNT(p.id)')
                ->where('p.author = :userId')
                ->setParameter('userId', $userId)
                ->andWhere('ir.enabled = true AND p.enabled = true');
        
         return  $qb
                    ->getQuery()
                    ->getSingleScalarResult();
    }
    
    public function getOneUserPropositions($userId, $limit){
        $qb = $this->createQueryBuilder('p')
                ->where('p.author = :userId')
                ->setParameter('userId', $userId)
                ->leftJoin('p.image','i')
                ->addSelect('i')
                ->leftJoin('p.imageRequest', 'ir')
                ->addSelect('ir')
                ->orderBy('p.createdDate', 'DESC')
                ->andWhere('ir.enabled = true AND p.enabled = true')
                ->setMaxResults($limit);
        
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }
    
}
