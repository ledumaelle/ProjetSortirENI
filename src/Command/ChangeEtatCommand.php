<?php

namespace App\Command;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
            if ($etatAnnulee->getId() !== $sortie->getEtat()->getId()) {
                $today = new DateTime();
                $todayForCloture = new DateTime();
                $todayForCloture->setTime(0, 0, 0);
                $etatInitialSortie = $sortie->getEtat()->getId();
                $duree = date_interval_create_from_date_string($sortie->getDuree() . ' minutes');
                $dateFin = date_add(new DateTime($sortie->getDateHeureDebut()->format('Y-m-d H:i')), $duree);

                if ($sortie->getDateLimiteInscription() < $todayForCloture || count($sortie->getInscriptions()) === $sortie->getNbInscriptionsMax()) {
                    $sortie->setEtat($etatClouturee);
                }


                if ($sortie->getDateLimiteInscription() >= $todayForCloture && count($sortie->getInscriptions()) < $sortie->getNbInscriptionsMax()) {
                    $sortie->setEtat($etatOuverte);
                }

                if ($sortie->getDateHeureDebut() <= $today && $dateFin > $today) {
                    $sortie->setEtat($etatEnCours);
                }

                if ($dateFin <= $today) {
                    $sortie->setEtat($etatPassee);
                }

                if ($etatInitialSortie !== $sortie->getEtat()->getId()) {
                    $this->entityManager->persist($sortie);
                }
            }
        }

        $this->entityManager->flush();

        return 0;
    }
}
