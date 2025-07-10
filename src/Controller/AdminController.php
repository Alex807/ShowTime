<?php

namespace App\Controller;

use App\Entity\UserAccount;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    #[Route('/promote/{id}', name: 'app_promote_user', requirements: ['id' => '\d+'])]
    public function promoteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(UserAccount::class)->find($id);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $currentUser = $this->getUser();
        $currentUser->promoteUser($user);

        $entityManager->flush();

        $this->addFlash('success', 'User promoted to next role successfully!');
        return $this->redirectToRoute('profile', ['id' => $user->getId()]);
    }

    //demoteUSER(duce user inapoi de la ADMIN la user->cn apeleaza sa fie FOUNDER)

    #[Route('/admin/users', name: 'app_admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        $users = $entityManager->getRepository(UserAccount::class)->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $users,
        ]);
    }
}
