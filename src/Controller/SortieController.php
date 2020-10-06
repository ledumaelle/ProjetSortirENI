<?php


namespace App\Controller;


use App\Entity\Sortie;
use App\Form\SortieType;
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
    public function creerSortie(Request $request){

        $sortie= new Sortie();

        $form = $this->createForm(SortieType::class,$sortie);

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


