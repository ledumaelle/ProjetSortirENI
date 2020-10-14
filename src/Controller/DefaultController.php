<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Mobile_Detect;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @param SortieRepository      $repo
     * @param PaginatorInterface    $paginator
     * @param Request               $request
     * @param CampusRepository      $campusRepository
     * @param ParticipantRepository $participantRepository
     * @return Response
     * @throws Exception
     */
    public function index(SortieRepository $repo, PaginatorInterface $paginator, Request $request, CampusRepository $campusRepository, ParticipantRepository $participantRepository)
    {
        $userName = $this->getUser()
                         ->getUsername();
        /** @var Participant $user */
        $user = $participantRepository->findOneBy(["mail" => $userName]);

        if (empty($user)) {
            throw $this->createNotFoundException("User non trouvé.");
        }

        //$repo->updateEtatSorties();
        $mobileDetect = new Mobile_Detect();
        $isMobile = $mobileDetect->isMobile();
        $repo->updateEtatSorties();

        $campus = $campusRepository->getAll()
                                   ->getResult();
        $nbSorties = 0;
        //Appliquer les filtres
        $params = $request->query->all();
        $params = array_filter($params);
        if ($isMobile) {
            $sorties = $repo->getSortiesByParticipant($user);
            $nbSorties = count($sorties);
        } else {
            $campus = $campusRepository->getAll()
                                       ->getResult();

            $params['participant_id'] = $user->getId();

            $query = $repo->getSorties($params);

            $sorties = $paginator->paginate($query, /* query NOT result */ $request->query->getInt('page', 1), /*page number*/ 4 /*limit per page*/);
            $nbSorties = $sorties->getTotalItemCount();
        }

        return $this->render('default/home.html.twig', [
            'sorties' => $sorties,
            'nbSorties' => $nbSorties,
            'campus' => $campus,
            'nomSortie' => $params['nomSortie'] ?? '',
            'campusParam' => $params['campus'] ?? '',
            'dateDebut' => (isset($params['dateDebut_submit']) && $params['dateDebut_submit']) ? date_create($params['dateDebut_submit'])->format('d/m/Y') : '',
            'dateFin' => (isset($params['dateFin_submit']) && $params['dateFin_submit']) ? date_create($params['dateFin_submit'])->format('d/m/Y') : '',
            'isOrganisateur' => $params['isOrganisateur'] ?? '',
            'isInscrit' => $params['isInscrit'] ?? '',
            'isNotInscrit' => $params['isNotInscrit'] ?? '',
            'isSortiesPassees' => $params['isSortiesPassees'] ?? '',
            'isMobile' => $isMobile,
        ]);
    }
}
