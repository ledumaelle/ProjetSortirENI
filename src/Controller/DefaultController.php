<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /** @var KernelInterface */
    protected $kernel;

    /**
     * @Route("/home", name="app_homepage")
     * @param SortieRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CampusRepository $campusRepository
     * @param ParticipantRepository $participantRepository
     * @return Response
     */
    public function index(SortieRepository $repo, PaginatorInterface $paginator, Request $request, CampusRepository $campusRepository, ParticipantRepository $participantRepository)
    {
        /** @var Participant $user */
        $user = $participantRepository->findOneByMail($this->getUser()->getUsername());

        if(empty($user)) {
            throw $this->createNotFoundException("User non trouvé.");
        }

        //$repo->updateEtatSorties();

        $campus = $campusRepository->getAll()->getResult();

        $params = [];
        //Appliquer les filtres
        $params = $request->query->all();
        $params = array_filter($params);

        $params['participant_id'] = $user->getId();

        $query = $repo->getSorties($params);

        $sorties = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('default/home.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus,
            'nomSortie' => $params['nomSortie'] ?? '' ,
            'campusParam' => $params['campus'] ?? '' ,
            'dateDebut' => (isset($params['dateDebut_submit']) && $params['dateDebut_submit']) ? date_create($params['dateDebut_submit'])->format('d/m/Y') : '' ,
            'dateFin' => (isset($params['dateFin_submit']) && $params['dateFin_submit']) ? date_create($params['dateFin_submit'])->format('d/m/Y') : '' ,
            'isOrganisateur' => $params['isOrganisateur'] ?? '' ,
            'isInscrit' => $params['isInscrit'] ?? '' ,
            'isNotInscrit' => $params['isNotInscrit'] ?? '' ,
            'isSortiesPassees' => $params['isSortiesPassees'] ?? '' ,
        ]);
    }
}
