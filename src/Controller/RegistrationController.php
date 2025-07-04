<?php

namespace App\Controller;

use App\Entity\UserAccount;
use App\Entity\UserDetails;
use App\Form\RegistrationForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new UserAccount();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Create UserDetails entity
            $userDetails = new UserDetails();
            $userDetails->setFirstName($form->get('firstName')->getData());
            $userDetails->setLastName($form->get('lastName')->getData());
            $userDetails->setAge($form->get('age')->getData());
            $userDetails->setPhoneNo($form->get('phoneNo')->getData());

            // Link the entities
            $userDetails->setUser($user);

            // Persist both entities
            $entityManager->persist($user);
            $entityManager->persist($userDetails);
            $entityManager->flush();

            return $this->redirectToRoute('app_home_page'); // path WHERE you want to redirect
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
