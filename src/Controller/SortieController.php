<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SortieController
 *
 * @package App\Controller
 *
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /** @var KernelInterface */
    protected $kernel;

    /**
     * SortieController constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     *
     * @Route("/create", name="sortie_create")
     * @param Request                $request
     * @param LoggerInterface        $logger
     * @param ParticipantRepository  $participantRepository
     * @param EtatRepository         $etatRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
    public function create(Request $request, LoggerInterface $logger, ParticipantRepository $participantRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        $sortie = new Sortie();
        $sortie->setDateHeureDebut(new DateTime());
        $sortie->setDateLimiteInscription(new DateTime());

        $userName = $this->getUser()
                         ->getUsername();

        /** @var Participant $user */
        $user = $participantRepository->findOneByMail($userName);

        $form = $this->createForm(SortieType::class, $sortie, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()
                                                                    ->getName()) {

                $etat = $etatRepository->findOneByLibelle('Créée');
            } else {
                $etat = $etatRepository->findOneByLibelle('Ouverte');
            }

            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $sortie->setSiteOrganisateur($user->getCampus());

            try {
                $sortie->setDateCreated();
            } catch (\Exception $e) {
                $logger->error($e);
            }

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash("success", "La sortie a bien été créée");

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('sortie/new-edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/update/{id}",
     *     name="update_sortie",
     *     requirements={"id": "\d+"})
     * @param Request                $request
     * @param Sortie                 $sortie
     * @param LoggerInterface        $logger
     * @param ParticipantRepository  $participantRepository
     * @param EtatRepository         $etatRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Request $request, Sortie $sortie, LoggerInterface $logger, ParticipantRepository $participantRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        if ($sortie->getEtat()
                   ->getId() != Etat::CREEE) {
            $this->addFlash("warning", "Vous ne pouvez plus modifier cette sortie");

            return $this->redirectToRoute('app_homepage');
        }

        $userName = $this->getUser()
                         ->getUsername();

        $user = $participantRepository->findOneByMail($userName);

        $form = $this->createForm(SortieType::class, $sortie, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()
                                                                    ->getName()) {
                $etat = $etatRepository->findOneByLibelle('Créée');
            } else {
                $etat = $etatRepository->findOneByLibelle('Ouverte');
            }

            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $sortie->setSiteOrganisateur($user->getCampus());

            try {
                $sortie->setDateCreated();
            } catch (\Exception $e) {
                $logger->error($e);
            }

            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash("success", "La sortie a bien été modifiée");

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('sortie/new-edit.html.twig', [
            'form' => $form->createView(),
            'sortieId' => $sortie->getId(),
            'isUpdate' => true,
        ]);
    }

    /**
     *
     * @Route("/subscribe/{id}",
     *     name="inscrit_sortie",
     *     requirements={"id": "\d+"})
     * @param Sortie                 $sortie
     * @param SortieRepository       $sortieRepository
     * @param ParticipantRepository  $participantRepo
     * @param EntityManagerInterface $entityManager
     * @return Response
     *
     * @throws Exception
     */
    public function inscriptionSortie(Sortie $sortie, SortieRepository $sortieRepository, ParticipantRepository $participantRepo, EntityManagerInterface $entityManager)
    {


        $userName = $this->getUser()
                         ->getUsername();

        $user = $participantRepo->findOneByMail($userName);

        foreach ($sortie->getInscriptions() as $inscription) {

            if ($inscription->getParticipant() == $user) {


                $this->addFlash("warning", "Vous êtes déjà inscrit pour cette sortie");

                return $this->redirectToRoute('app_homepage');
            }
        }

        $newInscription = new Inscription();

        $newInscription->setDateCreated();
        $newInscription->setDateInscription(new DateTime());
        $newInscription->setParticipant($user);

        $sortie->addInscription($newInscription);

        $entityManager->persist($sortie);
        $entityManager->persist($newInscription);
        $entityManager->flush();

        $sortieRepository->updateEtatSorties();

        $this->addFlash("success", "Votre inscription a bien été prise en compte");

        return $this->redirectToRoute('app_homepage');
    }

    /**
     *
     * @Route("/unsubscribe/{id}",
     *     name="desiste_sortie",
     *     requirements={"id": "\d+"})
     * @param Sortie                 $sortie
     * @param ParticipantRepository  $participantRepo
     * @param SortieRepository       $sortieRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
    public function desisteSortie(Sortie $sortie, ParticipantRepository $participantRepo, SortieRepository $sortieRepository, EntityManagerInterface $entityManager)
    {


        $userName = $this->getUser()
                         ->getUsername();

        $user = $participantRepo->findOneByMail($userName);

        foreach ($sortie->getInscriptions() as $inscription) {

            if ($inscription->getParticipant() == $user) {


                $sortie->removeInscription($inscription);

                $entityManager->persist($sortie);
                $entityManager->persist($inscription);
                $entityManager->flush();

                $this->addFlash("success", "Votre désistement a bien été pris en compte");

                return $this->redirectToRoute('app_homepage');
            }
        }

        $sortieRepository->updateEtatSorties();

        $this->addFlash("warning", "Vous n'êtes pas inscrit pour cette sortie");

        return $this->redirectToRoute('app_homepage');
    }

    /**
     *
     * @Route("/cancel/{id}",
     *     name="annul_sortie",
     *     requirements={"id": "\d+"})
     * @param Sortie                 $sortie
     * @param ParticipantRepository  $participantRepo
     * @param EtatRepository         $etatRepo
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
    public function annulerSortie(Sortie $sortie, ParticipantRepository $participantRepo, EtatRepository $etatRepo, EntityManagerInterface $entityManager)
    {

        $userName = $this->getUser()
                         ->getUsername();

        /** @var Participant $user */
        $user = $participantRepo->findOneByMail($userName);

        if ($sortie->getOrganisateur() !== $user || !$user->isAdmin()) {
            $this->addFlash("warning", "Vous n'êtes pas organisateur pour cette sortie");

            return $this->redirectToRoute('app_homepage');
        } else if ($sortie->getEtat()
                          ->getLibelle() == 'Annulée') {
            $this->addFlash("warning", "La sortie a déjà été annulée");

            return $this->redirectToRoute('app_homepage');
        } else if ($sortie->getDateHeureDebut() < new DateTime()) {
            $this->addFlash("warning", "La sortie a déjà commencée et ne peut plus être annulée");

            return $this->redirectToRoute('app_homepage');
        }

        $etat = $etatRepo->findOneByLibelle('Annulée');
        $sortie->setEtat($etat);
        $entityManager->persist($sortie);

        $entityManager->flush();

        $this->addFlash("success", "La sortie a bien été annulée");

        return $this->redirectToRoute('app_homepage');
    }
}


