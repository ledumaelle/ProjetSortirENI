<?php


namespace App\Controller;


use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SortieType;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{

    /**
     *
     * @Route("/sortie/creer")
     * @param Request $request
     * @return Response
     *
     */
    public function creerSortie(Request $request,LoggerInterface $logger){

        $sortie= new Sortie();

        $em=$this->getDoctrine()->getManager();

        $userName=$this->getUser()->getUsername();

        $user = $em->getRepository(Participant::class)->findOneByMail($userName);


        $logger->info($user->getNom());




        $form = $this->createForm(SortieType::class,$sortie,array('user'=>$user));

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //TODO EM

            return $this->render('sortie/new-edit.html.twig',[
                'form'  =>$form->createView()
            ]);
        }


        return $this->render('sortie/new-edit.html.twig',[
            'form'  =>$form->createView()
        ]);
    }

}


