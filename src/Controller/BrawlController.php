<?php

namespace App\Controller;

use App\Service\BrawlStarsApiService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrawlController extends AbstractController
{
    private BrawlStarsApiService $brawlStarsApiService;

    public function __construct(BrawlStarsApiService $brawlStarsApiService)
    {
        $this->brawlStarsApiService = $brawlStarsApiService;
    }

    #[Route('/brawlers', name: 'brawlers_list')]
    public function list(Request $request, PaginatorInterface $paginator): Response
    {
        $brawlers = $this->brawlStarsApiService->fetchBrawlers();

        $pagination = $paginator->paginate(
            $brawlers['items'],
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('brawlers/list.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}