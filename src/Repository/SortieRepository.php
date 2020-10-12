<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getSorties()
    {
        return $this->createQueryBuilder('s')
            ->leftJoin('s.organisateur', 'organisateur')
            ->addSelect('organisateur')
            ->leftJoin('s.siteOrganisateur', 'site_organisateur')
            ->addSelect('site_organisateur')
            ->leftJoin('s.etat', 'etat')
            ->addSelect('etat')
            ->leftJoin('s.lieu', 'lieu')
            ->leftJoin('s.inscriptions', 'inscrits')
            ->addSelect('inscrits')
            ->addSelect('lieu')
            ->getQuery();
    }

}
