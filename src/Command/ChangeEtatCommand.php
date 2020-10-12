<?php

namespace App\Command;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Date;

class ChangeEtatCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'change-etat';
    /** @var SortieRepository */
    protected $sortieRepository;
    /** @var EtatRepository */
    protected $etatRepository;
    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * ChangeEtatCommand constructor.
     * @param SortieRepository $sortieRepository
     * @param EtatRepository $etatRepository
     * @param EntityManagerInterface $entityManager
     * @param string|null $name
     */
    public function __construct(SortieRepository $sortieRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager, string $name = null)
    {
        parent::__construct($name);
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
        $this->entityManager = $entityManager;
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Regarde le statut de chaque sortie et le modifie');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var Etat $etatOuverte */
        $etatOuverte = $this->etatRepository->findOneBy(['libelle' => 'Ouverte']);
        /** @var Etat $etatClouturee */
        $etatClouturee = $this->etatRepository->findOneBy(['libelle' => 'Clôturée']);
        /** @var Etat $etatEnCours */
        $etatEnCours = $this->etatRepository->findOneBy(['libelle' => 'Activité en cours']);
        /** @var Etat $etatPassee */
        $etatPassee = $this->etatRepository->findOneBy(['libelle' => 'Passée']);
        /** @var Etat $etatAnnulee */
        $etatAnnulee = $this->etatRepository->findOneBy(['libelle' => 'Annulée']);

        $sorties = $this->sortieRepository->findAll();

        foreach ($sorties as $sortie) {
            $today = new DateTime();
            $etatSortie = $sortie->getEtat()->getId();
            $duree = DateInterval::createFromDateString($sortie->getDuree() . ' minutes');
            $dateFin = date_add($sortie->getDateHeureDebut(), $duree);

            if ($etatSortie !== $etatClouturee->getId() && $dateFin > $today) {
                if ($sortie->getDateLimiteInscription() < $today || count($sortie->getInscriptions()) === $sortie->getNbInscriptionsMax()) {
                    $sortie->setEtat($etatClouturee);
                }
            }

            if ($etatSortie !== $etatOuverte->getId()) {
                if ($sortie->getDateLimiteInscription() >= $today && count($sortie->getInscriptions()) < $sortie->getNbInscriptionsMax()) {
                    $sortie->setEtat($etatOuverte);
                }
            }

            if ($etatSortie !== $etatEnCours->getId()) {
                if ($sortie->getDateHeureDebut() <= $today && $dateFin > $today) {
                    $sortie->setEtat($etatEnCours);
                }
            }

            if ($etatSortie !== $etatPassee->getId()) {
                if ($dateFin <= $today) {
                    $sortie->setEtat($etatPassee);
                }
            }

            $this->entityManager->persist($sortie);
        }

        $this->entityManager->flush();

        return 0;
    }
}
