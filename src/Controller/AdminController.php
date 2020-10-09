<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller
 *
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/utilisateurs", name="app_admin_utilisateurs")
     * @param ParticipantRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    public function listUtilisateurs(ParticipantRepository $repo, PaginatorInterface $paginator,Request $request)
    {
        $query = $repo->getUtilisateursWithCampus();

        $participants = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('admin/listUtilisateurs.html.twig', [
            'participants' => $participants
        ]);
    }

    /**
     * @Route("/utilisateur/{id}/desactiver", name="app_admin_utilisateur_desactiver", requirements={"id": "\d+"})
     * @param $id
     * @param ParticipantRepository $participantRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function desactiverUtilisateur($id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);

        if (empty($participant)) {
            throw $this->createNotFoundException("Participant non trouvé");
        }

        $participant->setActif(false);
        $entityManager->persist($participant);
        $entityManager->flush();

        $this->addFlash("success", "L'utilisateur a bien été désactivé temporairement ! ");
        return $this->redirectToRoute("app_admin_utilisateurs");
    }

    /**
     * @Route("/utilisateur/{id}/reactiver", name="app_admin_utilisateur_reactiver", requirements={"id": "\d+"})
     * @param $id
     * @param ParticipantRepository $participantRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function reactiverUtilisateur($id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        $participant = $participantRepository->find($id);

        if (empty($participant)) {
            throw $this->createNotFoundException("Participant non trouvé");
        }

        $participant->setActif(true);
        $entityManager->persist($participant);
        $entityManager->flush();

        $this->addFlash("success", "L'utilisateur a bien été réactivé ! ");
        return $this->redirectToRoute("app_admin_utilisateurs");
    }

    /**
     * @Route("/utilisateur/{id}/delete", name="app_admin_utilisateur_delete")
     * @param $id
     * @param ParticipantRepository $participantRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete($id, ParticipantRepository $participantRepository, EntityManagerInterface $entityManager)
    {
        if(!is_numeric($id)) {
            throw $this->createNotFoundException("Paramètre invalide.");
        }

        $participant = $participantRepository->find($id);

        if (empty($participant)) {
            throw $this->createNotFoundException("Participant non trouvé");
        }

        $entityManager->remove($participant);
        $entityManager->flush();

        $this->addFlash("success", "L'utilisateur a bien été supprimé ! ");
        return $this->redirectToRoute("app_admin_utilisateurs");
    }
}
