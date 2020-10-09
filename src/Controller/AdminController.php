<?php

namespace App\Controller;

use App\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     */
    public function listUtilisateurs()
    {
        $participantRepository = $this->getDoctrine()->getRepository(Participant::class);

        $participants = $participantRepository->getUtilisateursWithCampus();

        return $this->render('admin/listUtilisateurs.html.twig', [
            'participants' => $participants
        ]);
    }
}
