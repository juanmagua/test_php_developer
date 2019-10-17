<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use App\Repository\UserRepository;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */

    
    public function findAllByRol($roles, $user)
    {
        $result =  $this->createQueryBuilder('e');

        if(in_array(Role::ROLE_GROUP, $roles)){
            $result = $result->innerJoin(User::class, 'u')
                            ->innerJoin(Group::class, 'g')
                            ->andWhere('g.id IN (:val)')
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

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneRandom(): ?Event
    {
        $results =  $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();

            $key = array_rand($results);

            return $results[$key]; 
        ;
    }
}
