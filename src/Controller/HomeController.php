<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(Request $request, BienRepository $repo): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        $criteria = $form->isSubmitted() && $form->isValid() ? $form->getData() : [];
        $biens = $repo->search($criteria ?? []);

        return $this->render('home/index.html.twig', [
            'searchForm' => $form,
            'biens' => $biens,
        ]);
    }
}
