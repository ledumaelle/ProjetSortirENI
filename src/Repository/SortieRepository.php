<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * SortieRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param KernelInterface $kernel
     */
    public function __construct(ManagerRegistry $registry, KernelInterface $kernel)
    {
        parent::__construct($registry, Sortie::class);
        $this->kernel = $kernel;
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

    /**
     * @param $participant
     * @return mixed
     */
    public function getSortiesByParticipantId($participant)
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
                    ->where('s.organisateur = :organisateur')
                    ->setParameter('organisateur', $participant)
                    ->getQuery()
                    ->execute();
    }

    /**
     * @throws Exception
     */
    public function updateEtatSorties()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'change-etat',
        ]);

        $output = new BufferedOutput();

        $application->run($input, $output);

        $output->fetch();
    }
}
