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

        $reviews = $userDetails->getUser()->getEditionReviews();
        $purchases = $userDetails->getUser()->getPurchases();

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
        $userDetails = $entityManager->getRepository(UserDetails::class)
            ->findOneBy(['user' => $user]);

        $reviews = $userDetails->getUser()->getEditionReviews();

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
        $userDetails = $entityManager->getRepository(UserDetails::class)
            ->findOneBy(['user' => $user]);

        $purchases = $userDetails->getUser()->getPurchases();

        return $this->render('profile/purchases.html.twig', [
            'user' => $user,
            'userDetails' => $userDetails,
            'purchases' => $purchases,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/api/search/users', name: 'api_search_users', methods: ['GET'])]
    public function searchUsers(Request $request, UserDetailsRepository $userDetailsRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        $limit = $request->query->get('limit', 10);

        if (strlen($query) < 2) {
            return new JsonResponse([
                'users' => [],
                'message' => 'Please enter at least 2 characters'
            ]);
        }

        $users = $userDetailsRepository->searchUsers($query, $limit);

        $results = [];
        foreach ($users as $userDetail) {
            $results[] = [
                'id' => $userDetail->getId(),
                'firstName' => $userDetail->getFirstName(),
                'lastName' => $userDetail->getLastName(),
                'email' => $userDetail->getUser()->getEmail(),
                'phoneNo' => $userDetail->getPhoneNo(),
                'age' => $userDetail->getAge(),
                'fullName' => $userDetail->getFirstName() . ' ' . $userDetail->getLastName(),
                'profileImage' => $userDetail->getProfileImage(),
                'roles' => $userDetail->getUser()->getRoles()
            ];
        }

        return new JsonResponse([
            'users' => $results,
            'count' => count($results)
        ]);
    }
}
