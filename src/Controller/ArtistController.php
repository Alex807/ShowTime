<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistTypeForm;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/artists')]
#[IsGranted('ROLE_USER')]
final class ArtistController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'artist_index', methods: ['GET'])]
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

        return $this->render('artist/index.html.twig', [
            'artists' => $artists,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'artist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession(); // needed for displaying flashMessages
        $session->start();

        $artist = new Artist();
        $form = $this->createForm(ArtistTypeForm::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($artist);
                $entityManager->flush();

                $this->addFlash('success', 'Artist created successfully!');
                $request->getSession()->getFlashBag()->clear(); //this line prevent the propagation of flash msj to redirected page after success

                return $this->redirectToRoute('artist_index');

            } else {
                // Handle constraints from FORM
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('artist/new.html.twig', [
            'artistForm' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'artist_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Artist $artist, Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $session->start();

        $form = $this->createForm(ArtistTypeForm::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $entityManager->flush();
                $this->addFlash('success', 'Artist updated successfully!');

                // handle Turbo requests separately
                if ($request->headers->get('Turbo-Frame')) {
                    $response = new Response(null, Response::HTTP_SEE_OTHER);
                    $response->headers->set('Location', $this->generateUrl('artist_index'));
                    return $response;
                }
                return $this->redirectToRoute('artist_index');

            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('artist/edit.html.twig', [
            'artist' => $artist,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'artist_show', requirements: ['id' => '\d+'], methods: ['GET'])] // shows details of a single artist
    public function show(Artist $artist): Response
    {
        return $this->render('artist/details.html.twig', [
            'artist' => $artist,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'artist_delete', requirements: ['id' => '\d+'], methods: ['POST'])] // POST method to avoid browser issues with DELETE
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
