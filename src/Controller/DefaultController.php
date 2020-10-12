<?php

namespace App\Controller;

use App\Repository\CampusRepository;
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
     * DefaultController constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Route("/home", name="app_homepage")
     * @param SortieRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param CampusRepository $campusRepository
     * @return Response
     * @throws Exception
     */
    public function index(SortieRepository $repo, PaginatorInterface $paginator, Request $request, CampusRepository $campusRepository)
    {
        $campus = $campusRepository->getAll()->getResult();


        $query = $repo->getSorties();

        $sorties = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );

        $this->changeEtat();

        return $this->render('default/home.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
        ]);
    }

    /**
     * @throws Exception
     */
    protected function changeEtat()
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'change-etat'
        ));

        $output = new BufferedOutput();

        $application->run($input, $output);

        $output->fetch();
    }
}
