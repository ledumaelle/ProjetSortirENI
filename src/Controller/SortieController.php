<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\AnnulationType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Mobile_Detect;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
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

    private $logger;

    /**
     * SortieController constructor.
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel,LoggerInterface $logger)
    {
        $this->kernel = $kernel;
        $this->logger=$logger;
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
        $date = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $date->setTime($date->format('H'), $date->format('i'), null);
        $sortie->setDateHeureDebut($date);
        $sortie->setDateLimiteInscription($date);

        $mail = $this->getUser()
                     ->getUsername();

        /** @var Participant $user */
        $user = $participantRepository->findOneByMail($mail);

        try {

            $form = $this->createForm(SortieType::class, $sortie, ['user' => $user]);

            $form->handleRequest($request);

            $messageSuccess = "";
            if ($form->isSubmitted() && $form->isValid()) {

                if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()
                                                                        ->getName()) {

                    $etat = $etatRepository->find(Etat::CREEE);
                    $messageSuccess = "La sortie a bien été créée mais est en attente de publication.";
                } else {

                    $etat = $etatRepository->find(Etat::OUVERTE);
                    $messageSuccess = "La sortie a bien été créée et publiée.";
                }

            $listUser=$form->get('userInscrit')->getViewData();



            foreach ($listUser as $idInscrit){

                $inscrit=$participantRepository->findOneById($idInscrit);

                $inscription = new Inscription();
                $inscription->setDateInscription(new DateTime());
                $inscription->setParticipant($inscrit);
                $inscription->setDateCreated();
                $inscription->setSortie($sortie);
                $sortie->addInscription($inscription);
                $entityManager->persist($inscription);

            }

            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $sortie->setSiteOrganisateur($user->getCampus());

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash("success", $messageSuccess);

                return $this->redirectToRoute('app_homepage');
            }
        } catch (Exception $exception) {


            $logger->error($exception->getMessage());

            $this->addFlash("danger", "Erreur lors de la création de la visite.");

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

        $mail = $this->getUser()
                     ->getUsername();

        $user = $participantRepository->findOneByMail($mail);

        $date = $sortie->getDateHeureDebut();
        $date->setTime($date->format('H'), $date->format('i'), null);
        $sortie->setDateHeureDebut($date);


        try {

            $form = $this->createForm(SortieType::class, $sortie, ['user' => $user]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()
                                                                        ->getName()) {
                    $etat = $etatRepository->find(Etat::CREEE);
                } else {
                    $etat = $etatRepository->find(Etat::OUVERTE);
                }


                if($sortie->getIsPrivate()) {
                    $listUser = $form->get('userInscrit')->getViewData();


                    foreach ($listUser as $idInscrit) {

                        $inscrit = $participantRepository->findOneById($idInscrit);

                        $inscription = new Inscription();
                        $inscription->setDateInscription(new DateTime());
                        $inscription->setParticipant($inscrit);
                        $inscription->setDateCreated();
                        $inscription->setSortie($sortie);
                        $sortie->addInscription($inscription);
                        $entityManager->persist($inscription);

                    }

                    $listUser2 = $form->get('userAll')->getViewData();
                    foreach ($listUser2 as $idInscrit) {

                        foreach ($sortie->getInscriptions() as $inscription) {
                            if ($inscription->getParticipant()->getId() == $idInscrit) {
                                $sortie->getInscriptions()->removeElement($inscription);
                            }
                        }

                    }
                }


                $sortie->setEtat($etat);
                $sortie->setOrganisateur($user);
                $sortie->setSiteOrganisateur($user->getCampus());

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash("success", "La sortie a bien été modifiée");

                return $this->redirectToRoute('app_homepage');
            }
        } catch (Exception $exception) {

            $this->addFlash("error", "Erreur lors de la modification de la visite.");

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

        $mail = $this->getUser()
                     ->getUsername();

        $user = $participantRepo->findOneByMail($mail);

        foreach ($sortie->getInscriptions() as $inscription) {

            if ($inscription->getParticipant() === $user) {

                $this->addFlash("warning", "Vous êtes déjà inscrit pour cette sortie");

                return $this->redirectToRoute('app_homepage');
            }
        }

        try {


            $newInscription = new Inscription();

            $newInscription->setDateInscription(new DateTime());
            $newInscription->setParticipant($user);

            $sortie->addInscription($newInscription);

            $entityManager->persist($sortie);
            $entityManager->persist($newInscription);
            $entityManager->flush();

            $sortieRepository->updateEtatSorties();

            $this->addFlash("success", "Votre inscription a bien été prise en compte.");
        } catch (Exception $exception) {

            $this->addFlash("danger", "Erreur lors de l'inscription à la visite.");
        }

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

        try {

            $mail = $this->getUser()
                         ->getUsername();

            $user = $participantRepo->findOneByMail($mail);

            $participantInscrit = false;
            foreach ($sortie->getInscriptions() as $inscription) {

                if ($inscription->getParticipant() === $user) {

                    $participantInscrit = true;

                    $sortie->removeInscription($inscription);

                    $entityManager->persist($sortie);
                    $entityManager->persist($inscription);
                    $entityManager->flush();

                    $this->addFlash("success", "Votre désistement a bien été pris en compte");

                    $sortieRepository->updateEtatSorties();
                }
            }

            if (!$participantInscrit) {

                $this->addFlash("warning", "Vous n'êtes pas inscrit pour cette sortie");
            }
        } catch (Exception $exception) {

            $this->addFlash("error", "Erreur lors du désistement à la visite.");
        }

        return $this->redirectToRoute('app_homepage');
    }

    /**
     *
     * @Route("/cancel/{id}",
     *     name="sortie_annuler",
     *     requirements={"id": "\d+"})
     * @param                        $id
     * @param SortieRepository       $repo
     * @param ParticipantRepository  $participantRepo
     * @param EtatRepository         $etatRepo
     * @param EntityManagerInterface $entityManager
     * @param Request                $request
     * @return Response
     */
    public function annulerSortie($id, SortieRepository $repo, ParticipantRepository $participantRepo, EtatRepository $etatRepo, EntityManagerInterface $entityManager, Request $request)
    {

        $sortie = $repo->find($id);

        if (empty($sortie)) {
            throw $this->createNotFoundException("Sortie non trouvée.");
        }

        $mail = $this->getUser()
                     ->getUsername();

        /** @var Participant $user */
        $user = $participantRepo->findOneByMail($mail);

        if ($sortie->getOrganisateur() !== $user && !$user->isAdmin()) {

            $this->addFlash("warning", "Vous n'êtes pas organisateur pour cette sortie.");

            return $this->redirectToRoute('app_homepage');
        } else if ($sortie->getEtat()
                          ->getId() == Etat::ANNULEE) {

            $this->addFlash("warning", "La sortie a déjà été annulée.");

            return $this->redirectToRoute('app_homepage');
        } else if ($sortie->getDateHeureDebut() < new DateTime()) {

            $this->addFlash("warning", "La sortie a déjà commencé et ne peut plus être annulée.");

            return $this->redirectToRoute('app_homepage');
        }

        try {

            $formAnnulation = $this->createForm(AnnulationType::class, $sortie);

            $formAnnulation->handleRequest($request);

            if ($formAnnulation->isSubmitted() && $formAnnulation->isValid()) {

                $etat = $etatRepo->find(Etat::ANNULEE);
                $sortie->setEtat($etat);

                $entityManager->persist($sortie);
                $entityManager->flush();
                $this->addFlash("success", "La sortie a bien été annulée.");

                return $this->redirectToRoute('app_homepage');
            }
        } catch (Exception $exception) {

            $this->addFlash("danger", "Erreur lors de l'annulation de la visite.");

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('sortie/annulation.html.twig', [
            'formAnnulation' => $formAnnulation->createView(),
            'sortie' => $sortie,
        ]);
    }

    /**
     *
     * @Route("/{id}",
     *     name="show_sortie",
     *     requirements={"id": "\d+"})
     * @param                        $id
     * @param SortieRepository       $repo
     * @param ParticipantRepository  $participantRepo
     * @param EtatRepository         $etatRepo
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function showSortie($id, SortieRepository $repo, ParticipantRepository $participantRepo, EtatRepository $etatRepo, EntityManagerInterface $entityManager)
    {
        $detect = new Mobile_Detect();
        $isMobile = $detect->isMobile();
        $sortie = $repo->find($id);

        if (empty($sortie)) {
            throw $this->createNotFoundException("Sortie non trouvée.");
        }

        //si prive et user  affiche
        //si prive et pas user masque
        //sinon affich

        $private = $sortie->getIsPrivate();

        $mail = $this->getUser()
                     ->getUsername();

        $user = $participantRepo->findOneByMail($mail);

        $participantFound = false;
        if ($private) {

            if ($sortie->getOrganisateur() === $user) {

                $participantFound = true;
            } else {

                foreach ($sortie->getInscriptions() as $inscription) {

                    if ($inscription->getParticipant() === $user) {
                        $participantFound = true;
                    }
                }
            }
        }

        if ($private && !$participantFound) {

            throw $this->createNotFoundException("Sortie non trouvée.");
        }

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie,
            'private' => $private,
            'isMobile' => $isMobile,
        ]);
    }

    /**
     * @Route("/publish/{id}",
     *     name="sortie_publish",
     *     requirements={"id": "\d+"})
     * @param                        $id
     * @param EntityManagerInterface $entityManager
     * @param SortieRepository       $repo
     * @param EtatRepository         $etatRepository
     * @return RedirectResponse
     */
    public function publier($id, EntityManagerInterface $entityManager, SortieRepository $repo, EtatRepository $etatRepository)
    {

        $sortie = $repo->find($id);

        if (empty($sortie)) {
            throw $this->createNotFoundException("Sortie non trouvée.");
        }

        try {

            $etat = $etatRepository->find(Etat::OUVERTE);
            $sortie->setEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash("success", "La sortie a bien été publiée.");
        } catch (Exception $exception) {

            $this->addFlash("danger", "Erreur lors de l'affichage de la visite.");
        }

        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("/delete/{id}",
     *     name="sortie_delete",
     *     requirements={"id": "\d+"})
     * @param                        $id
     * @param EntityManagerInterface $entityManager
     * @param SortieRepository       $repo
     * @return RedirectResponse
     */
    public function delete($id, EntityManagerInterface $entityManager, SortieRepository $repo)
    {

        $sortie = $repo->find($id);

        if (empty($sortie)) {
            throw $this->createNotFoundException("Sortie non trouvée.");
        }

        try {

            $entityManager->remove($sortie);
            $entityManager->flush();

            $this->addFlash("success", "La sortie a bien été supprimée.");
        } catch (Exception $exception) {

            $this->addFlash("danger", "Erreur lors de l'affichage de la visite.");
        }

        return $this->redirectToRoute('app_homepage');
    }
}


