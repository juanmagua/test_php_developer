<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function findAllByRol($roles, $user)
    {
        $result =  $this->createQueryBuilder('e');

        if(in_array(Role::ROLE_GROUP, $roles)){
            
            $result = $result->innerJoin('e.user', 'media_user')
                            ->innerJoin('media_user.groups', 'user_group')
                            ->andWhere('user_group.id IN (:val)')
                            ->setParameter('val', $user->getGroupIds());

        }else if(in_array(Role::ROLE_USER, $roles)){
           
           $result = $result->andWhere('e.user = :val')
                   ->setParameter('val', $user->getId());

        }

            
        $result = $result->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    
        return $result;    
    }    

    // /**
    //  * @return Media[] Returns an array of Media objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Media
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
