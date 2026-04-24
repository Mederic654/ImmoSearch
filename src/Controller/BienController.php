<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Utilisateur;
use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BienController extends AbstractController
{
    #[Route('/bien/{id}', name: 'app_bien_show', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function show(Bien $bien, Request $request, EntityManagerInterface $em, BienRepository $bienRepository): Response
    {
        $visite = new Visite();
        $visite->setBien($bien);
        $form = $this->createForm(VisiteType::class, $visite);

        $user = $this->getUser();
        if ($user instanceof Utilisateur) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $visite->setUtilisateur($user);
                $em->persist($visite);
                $em->flush();
                $this->addFlash('success', 'Demande de visite envoyée.');
                return $this->redirectToRoute('app_bien_show', ['id' => $bien->getId()]);
            }
        }

        return $this->render('bien/show.html.twig', [
            'bien' => $bien,
            'visiteForm' => $form,
            'similaires' => $bienRepository->findSimilar($bien, 3),
        ]);
    }
}
