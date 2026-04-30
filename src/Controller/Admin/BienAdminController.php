<?php

namespace App\Controller\Admin;

use App\Entity\Adresse;
use App\Entity\Bien;
use App\Entity\BienCaracteristique;
use App\Form\BienType;
use App\Repository\BienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/agent/biens')]
#[IsGranted('ROLE_AGENT')]
class BienAdminController extends AbstractController
{
    #[Route('', name: 'app_agent_bien_index', methods: ['GET'])]
    public function index(BienRepository $repo): Response
    {
        return $this->render('admin/bien_index.html.twig', [
            'biens' => $repo->findBy([], ['dateCreation' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_agent_bien_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $bien = new Bien();
        $bien->setAdresse(new Adresse());
        $bien->setCaracteristique(new BienCaracteristique());

        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($bien);
            $em->flush();
            $this->addFlash('success', 'Bien créé.');
            return $this->redirectToRoute('app_agent_bien_index');
        }

        return $this->render('admin/bien_form.html.twig', ['form' => $form, 'bien' => $bien]);
    }

    #[Route('/{id}/edit', name: 'app_agent_bien_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Bien $bien, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BienType::class, $bien);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Bien mis à jour.');
            return $this->redirectToRoute('app_agent_bien_index');
        }

        return $this->render('admin/bien_form.html.twig', ['form' => $form, 'bien' => $bien]);
    }

    #[Route('/{id}/delete', name: 'app_agent_bien_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Bien $bien, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('delete' . $bien->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Jeton CSRF invalide.');
            return $this->redirectToRoute('app_agent_bien_index');
        }
        $em->remove($bien);
        $em->flush();
        $this->addFlash('success', 'Bien supprimé.');
        return $this->redirectToRoute('app_agent_bien_index');
    }
}
