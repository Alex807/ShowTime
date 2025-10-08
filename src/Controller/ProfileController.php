<?php

namespace App\Controller;

use App\Entity\UserDetails;
use App\Repository\UserDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userDetails = $entityManager->getRepository(UserDetails::class)
            ->findOneBy(['user' => $user]);


        if (!$userDetails) {
            throw $this->createNotFoundException('User details not found');
        }
        $userAccount = $userDetails->getUser();
        $reviews = $userAccount->getEditionReviews();
        $purchases = $userAccount->getPurchases();

        return $this->render('profile/profile.html.twig', [
            'user' => $user,
            'userDetails' => $userDetails,
            'reviews' => $reviews,
            'purchases' => $purchases,
        ]);
    }

    #[Route('/profile/reviews', name: 'user_reviews', methods: ['GET'])]
    public function userReviews(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userDetails = $entityManager->getRepository(UserDetails::class)
            ->findOneBy(['user' => $user]);

        if (!$userDetails) {
            throw $this->createNotFoundException('User details not found');
        }

        $reviews = $user->getEditionReviews();

        return $this->render('profile/reviews.html.twig', [
            'user' => $user,
            'userDetails' => $userDetails,
            'reviews' => $reviews,
        ]);
    }

    #[Route('/profile/purchases', name: 'user_purchases', methods: ['GET'])]
    public function userPurchases(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $userDetails = $entityManager->getRepository(UserDetails::class)
            ->findOneBy(['user' => $user]);

        if (!$userDetails) {
            throw $this->createNotFoundException('User details not found');
        }

        $purchases = $user->getPurchases();

        return $this->render('profile/purchases.html.twig', [
            'user' => $user,
            'userDetails' => $userDetails,
            'purchases' => $purchases,
        ]);
    }

}
