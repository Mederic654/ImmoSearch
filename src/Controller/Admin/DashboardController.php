<?php

namespace App\Controller\Admin;

use App\Repository\BienRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent')]
#[IsGranted('ROLE_AGENT')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_agent_dashboard', methods: ['GET'])]
    public function index(
        BienRepository $bienRepo,
        VisiteRepository $visiteRepo,
        UtilisateurRepository $userRepo,
    ): Response {
        return $this->render('admin/dashboard.html.twig', [
            'biensParStatut' => $bienRepo->countByStatut(),
            'visitesEnAttente' => $visiteRepo->findPending(),
            'totalBiens' => $bienRepo->count([]),
            'totalVisites' => $visiteRepo->countAll(),
            'totalUtilisateurs' => $userRepo->count([]),
        ]);
    }
}
