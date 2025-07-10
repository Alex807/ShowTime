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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

#[IsGranted('PUBLIC_ACCESS')] //this makes entire controller to be accessed by anyone
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        TokenStorageInterface $tokenStorage
    ): Response
    {
        $session = $request->getSession(); // needed for displaying flashMessages
        $session->start();

        $user = new UserAccount();
        $form = $this->createForm(RegistrationForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) { // Validate the entity manually
            if ($form->isValid()) {
                // Hash the password
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('Password')->getData()
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

                // Authenticate the user
                $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
                $tokenStorage->setToken($token);

                // Store the token in the session
                $session->set('_security_main', serialize($token));

                // Fire the login event to complete authentication
                $loginEvent = new InteractiveLoginEvent($request, $token); //to make auto-login after registration
                $eventDispatcher->dispatch($loginEvent, SecurityEvents::INTERACTIVE_LOGIN);

                return $this->redirectToRoute('profile');
            } else {
                // Handle constraints from FORM
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
                $request->getSession()->getFlashBag()->clear(); //this line prevent the propagation of flash msj to redirected page after success
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
