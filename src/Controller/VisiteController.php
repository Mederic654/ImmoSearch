<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile')]
#[IsGranted('ROLE_USER')]
class VisiteController extends AbstractController
{
    #[Route('/visites', name: 'app_profile_visites', methods: ['GET'])]
    public function mes(VisiteRepository $repo): Response
    {
        /** @var Utilisateur $u */
        $u = $this->getUser();
        return $this->render('visite/mes_visites.html.twig', [
            'visites' => $repo->findForUser($u),
        ]);
    }
}
