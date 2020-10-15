<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use DateInterval;
use DateTime;
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

    public function getSorties($params = [])
    {
        $qb = $this->createQueryBuilder('s')
                   ->leftJoin('s.organisateur', 'organisateur')
                   ->addSelect('organisateur')
                   ->leftJoin('s.siteOrganisateur', 'site_organisateur')
                   ->addSelect('site_organisateur')
                   ->leftJoin('s.etat', 'etat')
                   ->addSelect('etat')
                   ->leftJoin('s.lieu', 'lieu')
                   ->addSelect('lieu')
                   ->leftJoin('s.inscriptions', 'inscriptions')
                   ->addSelect('inscriptions')
                    ->leftJoin('inscriptions.participant', 'participants')
                    ->addSelect('participants')
                   ->andWhere('s.dateHeureDebut > :dateMoinsDunMois')
                   ->setParameter('dateMoinsDunMois', (new DateTime('now'))->sub(new DateInterval('P1M')));


        $qb->andWhere("s.isPrivate = true")
            ->andWhere("participants.id = :participant_id OR s.organisateur = :participant_id")
            ->setParameter('participant_id',$params['participant_id'])
            ->orWhere("s.isPrivate = false");

        if (isset($params['nomSortie'])) {
            $qb->andWhere('s.nom like :nomSortie')
               ->setParameter('nomSortie', '%' . $params['nomSortie'] . '%');
        }

        if (isset($params['campus'])) {
            $qb->andWhere('site_organisateur.id = :campus')
               ->setParameter('campus', $params['campus']);
        }

        if (isset($params['dateDebut'])) {
            $qb->andWhere('s.dateHeureDebut > :dateDebut')
               ->setParameter('dateDebut', $params['dateDebut_submit']);
        }

        if (isset($params['dateFin'])) {
            $qb->andWhere('s.dateHeureDebut < :dateFin')
               ->setParameter('dateFin', $params['dateFin_submit']);
        }

        if (isset($params['isOrganisateur'])) {
            $qb->andWhere('s.organisateur = :organisateur')
               ->setParameter('organisateur', $params['participant_id']);
        }

        if (isset($params['isInscrit'])) {
            $qb->andWhere('inscriptions.participant = :participantInscrit')
               ->setParameter('participantInscrit', $params['participant_id']);
        }

        if (isset($params['isNotInscrit'])) {
            $qb->andWhere('inscriptions.participant <> :participantNonInscrit')
               ->setParameter('participantNonInscrit', $params['participant_id']);
        }

        if (isset($params['isSortiesPassees'])) {
            $qb->andWhere('s.dateHeureDebut < :now')
               ->setParameter('now', date('Y-m-d'));
        }

        $qb->orderBy('s.dateHeureDebut', "ASC")
           ->orderBy('s.etat', 'ASC');

        return $qb->getQuery();
    }

    /**
     * @param Participant $user
     * @return mixed
     * @throws Exception
     */
    public function getSortiesByParticipant(Participant $user)
    {
        return $this->createQueryBuilder('s')
                    ->leftJoin('s.organisateur', 'organisateur')
                    ->addSelect('organisateur')
                    ->leftJoin('s.siteOrganisateur', 'site_organisateur')
                    ->addSelect('site_organisateur')
                    ->leftJoin('s.etat', 'etat')
                    ->addSelect('etat')
                    ->leftJoin('s.lieu', 'lieu')
                    ->addSelect('lieu')
                    ->leftJoin('s.inscriptions', 'inscriptions')
                    ->addSelect('inscriptions')
                    ->andWhere('s.dateHeureDebut > :dateMoinsDunMois')
                    ->setParameter('dateMoinsDunMois', (new DateTime('now'))->sub(new DateInterval('P1M')))
                    ->andWhere('s.organisateur = :user')
                    ->setParameter('user', $user)
                    ->orderBy('s.dateHeureDebut', "ASC")
                    ->orderBy('s.etat', 'ASC')
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
