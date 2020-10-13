<?php


namespace App\Controller;


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
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     *
     * @Route("/create", name="sortie_create")
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ParticipantRepository $participantRepository
     * @param EtatRepository $etatRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function create(Request $request, LoggerInterface $logger, ParticipantRepository $participantRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {
        $sortie = new Sortie();

        $userName = $this->getUser()->getUsername();

        /** @var Participant $user */
        $user = $participantRepository->findOneByMail($userName);


        $form = $this->createForm(SortieType::class, $sortie, array('user' => $user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()->getName()) {

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
            return $this->redirectToRoute('app_homepage');
        }


        return $this->render('sortie/new-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     *
     * L'edition ne fait pas parti des feature demander actuelement
     *
     */
    public function edit(Request $request, Sortie $sortie, LoggerInterface $logger, ParticipantRepository $participantRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager)
    {


        /*
        $userName = $this->getUser()->getUsername();

        $user = $participantRepository->findOneByMail($userName);





        $form = $this->createForm(SortieType::class, $sortie, array('user' => $user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()->getName()) {

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
            return $this->render('default/home.html.twig');
        }


        return $this->render('sortie/new-edit.html.twig', [
            'form' => $form->createView()
        ]);*/
    }

    /**
     *
     * @Route("/subscribe/{id}",
     *     name="inscrit_sortie",
     *     requirements={"id": "\d+"})
     * @param Sortie $sortie
     * @param SortieRepository $sortieRepository
     * @return Response
     *
     * @throws Exception
     */
    public function inscriptionSortie(Sortie $sortie, SortieRepository $sortieRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


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

        $em->persist($sortie);
        $em->persist($newInscription);
        $em->flush();

        $sortieRepository->updateEtatSorties();

        $this->addFlash("success", "Votre inscription a bien été prise en compte");
        return $this->redirectToRoute('app_homepage');
    }


    /**
     *
     * @Route("/unsubscribe/{id}",
     *     name="desiste_sortie",
     *     requirements={"id": "\d+"})
     * @param Sortie $sortie
     * @param SortieRepository $sortieRepository
     * @return Response
     *
     * @throws Exception
     */
    public function desisteSortie(Sortie $sortie, SortieRepository $sortieRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


        foreach ($sortie->getInscriptions() as $inscription) {

            if ($inscription->getParticipant() == $user) {


                $sortie->removeInscription($inscription);

                $em->persist($sortie);
                $em->persist($inscription);
                $em->flush();


                $this->addFlash("success", "Votre désistement a bien été pris en compte");
                return $this->redirectToRoute('app_homepage');
            }

        }

        $sortieRepository->updateEtatSorties();

        $this->addFlash("warning", "Vous n'êtes pas inscrit pour cette sortie");
        return $this->redirectToRoute('app_homepage');
    }
}


