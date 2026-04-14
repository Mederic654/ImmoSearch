<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        RoleRepository $roleRepo,
    ): Response {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plain = $form->get('plainPassword')->getData();
            $user->setMotDePasse($hasher->hashPassword($user, $plain));

            $defaultRole = $roleRepo->findByNom(Role::USER);
            if (!$defaultRole) {
                $defaultRole = (new Role())->setNom(Role::USER)->setDescription('Utilisateur standard');
                $em->persist($defaultRole);
            }
            $user->setRole($defaultRole);

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Compte créé, vous pouvez vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', ['form' => $form]);
    }
}
