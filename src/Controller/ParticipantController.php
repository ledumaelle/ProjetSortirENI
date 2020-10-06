<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/profil", name="participant_profil")
     */
    public function index()
    {
        return $this->render('participant/profil.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }
}
