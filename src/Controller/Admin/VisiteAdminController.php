<?php

namespace App\Controller\Admin;

use App\Entity\Visite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent/visites')]
#[IsGranted('ROLE_AGENT')]
class VisiteAdminController extends AbstractController
{
    #[Route('/{id}/statut', name: 'app_agent_visite_statut', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function changerStatut(Visite $visite, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('visite' . $visite->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_agent_dashboard');
        }

        $statut = $request->request->get('statut');
        if (in_array($statut, Visite::STATUTS, true)) {
            $visite->setStatut($statut);
            $em->flush();
            $this->addFlash('success', 'Statut mis à jour.');
        }
        return $this->redirectToRoute('app_agent_dashboard');
    }
}
