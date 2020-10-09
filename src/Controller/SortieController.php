<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SortieController
 * @package App\Controller
 *
 * @Route("/sortie")
 */
class SortieController extends AbstractController
{
    /**
     *
     * @Route("/create", name="sortie_create")
     * @param Request $request
     * @param LoggerInterface $logger
     * @param ParticipantRepository $participantRepository
     * @param EtatRepository $etatRepository
     * @return Response
     */
    public function create(Request $request, LoggerInterface $logger, ParticipantRepository $participantRepository, EtatRepository $etatRepository, EntityManagerInterface  $entityManager) {
        $sortie = new Sortie();

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
        ]);
    }


    /**
     *
     * @Route("/edit/{id}", name="sortie_edit", requirements={"id": "\d+"})
     * @param Request $request
     * @param Sortie $sortie
     * @param LoggerInterface $logger
     * @param ParticipantRepository $participantRepository
     * @param EtatRepository $etatRepository
     * @return Response
     */
    public function edit(Request $request, Sortie $sortie, LoggerInterface $logger, ParticipantRepository $participantRepository, EtatRepository $etatRepository, EntityManagerInterface $entityManager) {

        $userName = $this->getUser()->getUsername();

        $user = $participantRepository->findOneByMail($userName);


        //TODO check si sortie est editable (date,etat)


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
        ]);
    }

}


