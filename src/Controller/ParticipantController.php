<?php

namespace App\Controller;

use App\Form\ParticipantType;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="participant_show", requirements={"id": "\d+"})
     * @param $id
     * @param ParticipantRepository $repo
     * @return Response
     */
    public function show($id,ParticipantRepository $repo)
    {
        $participant = $repo->find($id);

        if(empty($participant)) {
            throw $this->createNotFoundException("Participant non trouvé");
        }

        return $this->render('participant/show.html.twig', [
            'participant' => $participant
        ]);
    }

    /**
     * @Route("/profil/{id}/edit", name="participant_edit", requirements={"id": "\d+"})
     * @param $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ParticipantRepository $repo
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function edit($id, Request $request, EntityManagerInterface $entityManager, ParticipantRepository $repo, UserPasswordEncoderInterface $passwordEncoder) {

        $participant = $repo->find($id);

        if(empty($participant)) {
            throw $this->createNotFoundException("Participant non trouvé");
        }

        if($participant->getMail() != $this->getUser()->getUsername()) {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit de modifier le profil d'un autre participant.");
        }

        $originalPassword = $participant->getPassword();

        $formParticipant = $this->createForm(ParticipantType::class, $participant);

        $formParticipant->handleRequest($request);

        if ($formParticipant->isSubmitted() && $formParticipant->isValid()) {

            try {

                $plainPassword = $formParticipant->get('motPasse')->getData();

                if (!empty(trim($plainPassword)))  {
                    $password = $passwordEncoder->encodePassword($participant, $participant->getPassword());
                    $participant->setMotPasse($password);
                }
                else {
                    $participant->setMotPasse($originalPassword);
                }

                $entityManager->persist($participant);
                $entityManager->flush();

                $this->addFlash("success", "Votre profil à bien été modifié ! ");
            } catch (Exception $exception) {
                $this->addFlash("error", $exception->getMessage());
            }

            return $this->redirectToRoute('participant_edit',[
                'id' => $participant->getId()
            ]);
        }

        return $this->render('participant/edit.html.twig', [
            'formParticipant' => $formParticipant->createView()
        ]);
    }
}
