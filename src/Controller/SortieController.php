<?php


namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class SortieController extends AbstractController
{

    /**
     *
     * @Route("/sortie/creer", name="app_sortie_creer")
     * @param Request $request
     * @return Response
     *
     */
    public function creerSortie(Request $request, LoggerInterface $logger)
    {

        $sortie = new Sortie();

        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


        $form = $this->createForm(SortieType::class, $sortie, array('user' => $user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()->getName()) {

                $etat = $em->getRepository(Etat::class)->findOneByLibelle('Créée');

            } else {
                $etat = $em->getRepository(Etat::class)->findOneByLibelle('Ouverte');
            }

            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $sortie->setSiteOrganisateur($user->getCampus());

            try {
                $sortie->setDateCreated();
            } catch (\Exception $e) {
                $logger->error($e);
            }

            $em->persist($sortie);
            $em->flush();
            return $this->render('default/home.html.twig');
        }


        return $this->render('sortie/new-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     *
     * @Route("/sortie/edit/{id}",
     *     name="edit_sortie",
     *     requirements={"id": "\d+"})
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
     *
     */
    public function editSortie(Request $request, Sortie $sortie, LoggerInterface $logger)
    {


        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


        //TODO check si sortie est editable (date,etat)


        $form = $this->createForm(SortieType::class, $sortie, array('user' => $user));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            if ($form->getClickedButton() && 'Enregistrer' === $form->getClickedButton()->getName()) {

                $etat = $em->getRepository(Etat::class)->findOneByLibelle('Créée');

            } else {
                $etat = $em->getRepository(Etat::class)->findOneByLibelle('Ouverte');
            }

            $sortie->setEtat($etat);
            $sortie->setOrganisateur($user);
            $sortie->setSiteOrganisateur($user->getCampus());

            try {
                $sortie->setDateCreated();
            } catch (\Exception $e) {
                $logger->error($e);
            }

            $em->persist($sortie);
            $em->flush();
            return $this->render('default/home.html.twig');
        }


        return $this->render('sortie/new-edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/sortie/inscription/{id}",
     *     name="inscrit_sortie",
     *     requirements={"id": "\d+"})
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
     *
     */
    public function inscriptionSortie(Request $request, Sortie $sortie, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


        foreach($sortie->getInscriptions() as $inscription){

            if($inscription->getParticipant()==$user) {


                return $this->render('sortie/confirmation.html.twig', [
                    'message' => 'vous etes deja inscrit pour cette sortie',
                    'class'=>'alert alert-warning'
                ]);
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

        return $this->render('sortie/confirmation.html.twig',[
            'message'=>'votre inscription a bien etait prise en compte',
            'class'=>'alert alert-success'
        ]);







    }


    /**
     *
     * @Route("/sortie/desiste/{id}",
     *     name="desiste_sortie",
     *     requirements={"id": "\d+"})
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
     *
     */
    public function desisteSortie(Request $request, Sortie $sortie, LoggerInterface $logger)
    {
        $em = $this->getDoctrine()->getManager();

        $userName = $this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


        foreach($sortie->getInscriptions() as $inscription){

            if($inscription->getParticipant()==$user) {


                

                $sortie->removeInscription($inscription);

                $em->persist($sortie);
                $em->persist($inscription);
                $em->flush();

                return $this->render('sortie/confirmation.html.twig',[
                    'message'=>'votre désistement a bien etait prise en compte',
                    'class'=>'alert alert-success'
                ]);
               
            }

        }

        return $this->render('sortie/confirmation.html.twig', [
            'message' => 'vous n\'etes pas inscrit pour cette sortie',
            'class'=>'alert alert-warning'
        ]);


        

    }




}


