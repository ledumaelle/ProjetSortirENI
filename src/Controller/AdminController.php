<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Campus;
use App\Entity\Participant;
use App\Form\ParticipantType;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    /**
     * @Route("/utilisateurs/add", name="app_admin_add_utilisateurs")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     */
    public function addUser(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {

        $participant = new Participant();

        $formParticipant = $this->createForm(ParticipantType::class, $participant);

        $formParticipant->handleRequest($request);

        if ($formParticipant->isSubmitted() && $formParticipant->isValid()) {

            try {
                $password = $passwordEncoder->encodePassword($participant, $participant->getPassword());
                $participant->setMotPasse($password);
                $participant->setAdministrateur($request->get('isAdmin') === true);
                $participant->setActif(true);
                $participant->setDateCreated();

                $entityManager->persist($participant);
                $entityManager->flush();

                $this->addFlash("success", "Votre profil à bien été modifié ! ");

                $participant = new Participant();
                $formParticipant = $this->createForm(ParticipantType::class, $participant);
            } catch (Exception $exception) {
                $this->addFlash("danger", $exception->getMessage());
            }

            return $this->render('participant/edit.html.twig', [
                'isAdmin' => true,
                'formParticipant' => $formParticipant->createView()
            ]);
        }

        return $this->render('participant/edit.html.twig', [
            'isAdmin' => true,
            'formParticipant' => $formParticipant->createView()
        ]);
    }

    /**
     * @Route("/utilisateurs/import", name="app_admin_import_users")
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse
     */
    public function importUsers(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        try {
            if ($_FILES["csvFile"]["size"] === 0) {
                $this->addFlash("danger", 'Veuillez choisir un fichier (.csv)!');

                return $this->redirectToRoute("app_admin_utilisateurs");
            }

            $campusRepository = $this->getDoctrine()->getRepository(Campus::class);
            $participantRepository = $this->getDoctrine()->getRepository(Participant::class);

            move_uploaded_file($_FILES["csvFile"]["tmp_name"], "import/" . $_FILES["csvFile"]["name"]);
            $users = fopen("import/" . $_FILES["csvFile"]["name"], 'r');

            while (($user = fgetcsv($users)) !== FALSE) {
                if (null !== $participantRepository->findOneBy(["mail" => $user[1]])) {
                    $this->addFlash("danger", 'Veuillez vérifier les informations utilisateurs!');

                    return $this->redirectToRoute("app_admin_utilisateurs");
                }

                $participant = new Participant();
                $participant->setPseudo($user[0]);
                $participant->setMail($user[1]);
                $participant->setNom($user[2]);
                $participant->setPrenom($user[3]);
                $participant->setMotPasse($passwordEncoder->encodePassword($participant, $user[4]));
                $participant->setTelephone($user[5]);

                /** @var Campus $campus */
                $campus = $campusRepository->findOneBy(["nom" => strtoupper($user[6])]);

                $participant->setCampus($campus);
                $participant->setAdministrateur($user[7]);

                $participant->setActif(true);
                $participant->setDateCreated();

                $entityManager->persist($participant);
            }

            $entityManager->flush();

            $this->addFlash("success", 'Les participants ont été importés avec succès!');

            return $this->redirectToRoute("app_admin_utilisateurs");
        } catch (Exception $exception) {
            $this->addFlash("danger", 'Une erreur est arrivée, veuillez contacter votre administrateur!');

            return $this->redirectToRoute("app_admin_utilisateurs");
        }
    }
}
