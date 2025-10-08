<?php

namespace App\Controller;

use App\Entity\UserAccount;
use App\Repository\UserAccountRepository;
use App\Repository\UserDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/users')]
#[IsGranted('ROLE_ADMIN')]
final class UserAccountController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'user_account_index', methods: ['GET'])]
    public function index(
        UserAccountRepository $userAccountRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        try {
            $query = $userAccountRepository->createQueryBuilder('u')
                ->orderBy('u.email', 'ASC') // Changed from u.roles to u.email
                ->getQuery();

            $users = $paginator->paginate(
                $query,
                $request->query->getInt('page', self::STARTING_PAGE_NO),
                self::ITEMS_PER_PAGE
            );

            return $this->render('user_account/index.html.twig', [
                'users' => $users,
            ], $response);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to load users: ' . $e->getMessage());
            return $this->render('user_account/index.html.twig', [
                'users' => [],
            ], $response);
        }
    }

    #[Route('/{id}', name: 'user_account_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(
        ?UserAccount $userAccount,
        UserDetailsRepository $userDetailsRepository
    ): Response {
        if (!$userAccount) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('user_account_index');
        }

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $userDetails = $userDetailsRepository->findOneBy(['user' => $userAccount]);
        if (!$userDetails) {
            $this->addFlash('error', 'User details not found.');
        }

        $reviews = $userAccount->getEditionReviews();
        $purchases = $userAccount->getPurchases();

        return $this->render('user_account/show.html.twig', [
            'userAccount' => $userAccount,
            'userDetails' => $userDetails,
            'reviews' => $reviews,
            'purchases' => $purchases,
        ], $response);
    }

    #[Route('/{id}/delete', name: 'user_account_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        ?UserAccount $userAccount,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$userAccount) {
            $this->addFlash('error', 'User not found.');
            return $this->redirectToRoute('user_account_index');
        }

        if ($this->getUser()->getId() === $userAccount->getId()) {
            $this->addFlash('error', 'You cannot delete your own account.');
            return $this->redirectToRoute('user_account_index');
        }

        if (!$this->isCsrfTokenValid('delete_user_account_' . $userAccount->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token!');
            return $this->redirectToRoute('user_account_index');
        }

        try {
            $entityManager->remove($userAccount);
            $entityManager->flush();
            $this->addFlash('success', 'User account deleted successfully!');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to delete user: ' . $e->getMessage());
        }

        $request->getSession()->getFlashBag()->clear();
        return $this->redirectToRoute('user_account_index');
    }
}
