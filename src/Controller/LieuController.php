<?php


namespace App\Controller;


use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\EtatRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LieuController
 * @package App\Controller
 *
 * @Route("/lieu")
 */
class LieuController extends AbstractController
{

    /**
     *
     * @Route("/create", name="lieu_create")
     * @param Request $request
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function create(Request $request, LoggerInterface $logger, EntityManagerInterface $entityManager)
    {

        $lieu = new Lieu();

        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $lieu->setDateCreated();
            } catch (\Exception $e) {
                $logger->error($e);
            }

            $entityManager->persist($lieu);
            $entityManager->flush();


            $this->addFlash("success", "Votre lieu a bien été créé");
            return $this->redirectToRoute('sortie_create');
        }


        return $this->render('lieu/new-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
