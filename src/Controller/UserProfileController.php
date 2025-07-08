<?php

namespace App\Controller;

use App\Entity\UserDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')] // Accessible by ROLE_USER and ROLE_ADMIN due to role hierarchy
final class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) { //make sure the user is logged-in
            return $this->redirectToRoute('app_login');
        }

        // Fetch UserDetails
        $userDetails = $entityManager->getRepository(UserDetails::class)
                                      ->findOneBy(['user' => $user]);

        return $this->render('user_profile/profile.html.twig', [
            'user' => $user,
            'userDetails' => $userDetails,
        ]);
    }
}
