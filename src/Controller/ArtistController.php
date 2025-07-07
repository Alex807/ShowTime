<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/artists')] // base route for this controller (entry point for artists)
final class ArtistController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('', name: 'artist_index', methods: ['GET'])]
    public function index(
        ArtistRepository $artistRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $artistRepository->createQueryBuilder('a')->getQuery();

        $artists = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('artist/profile.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[Route('/{id}', name: 'artist_show', methods: ['GET'])] // shows details of a single artist
    public function show(Artist $artist): Response
    {
        return $this->render('artist/details.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[Route('/{id}/delete', name: 'artist_delete', methods: ['POST'])] // POST method to avoid browser issues with DELETE
    public function delete(
        Artist $artist,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isCsrfTokenValid('delete_artist_' . $artist->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token!');
            return $this->redirectToRoute('artist_index');
        }

        $entityManager->remove($artist);
        $entityManager->flush();

        $this->addFlash('success', 'Artist deleted successfully!');
        return $this->redirectToRoute('artist_index');
    }
}
