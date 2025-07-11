<?php

namespace App\Controller;

use App\Entity\EditionArtist;
use App\Entity\FestivalEdition;
use App\Form\EditionArtistTypeForm;
use App\Repository\EditionArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/edition/{editionId}/artists')]
#[IsGranted('ROLE_ADMIN')]
final class EditionArtistController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'edition_artist_index', requirements: ['editionId' => '\d+'], methods: ['GET'])]
    public function index(
        int $editionId,
        EditionArtistRepository $editionArtistRepository,
        PaginatorInterface $paginator,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $request->getSession();
        $session->start();

        $festivalEdition = $entityManager->getRepository(FestivalEdition::class)->find($editionId);
        if (!$festivalEdition) {
            $this->addFlash('error', 'Festival edition not found.');
            return $this->redirectToRoute('festival_edition_index', ['festival' => $request->query->get('festival')]);
        }

        $query = $editionArtistRepository->createQueryBuilder('ea')
            ->where('ea.edition = :edition')
            ->setParameter('edition', $festivalEdition)
            ->orderBy('ea.performance_date', 'ASC')
            ->addOrderBy('ea.start_time', 'ASC')
            ->getQuery();

        $editionArtists = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('edition_artist/index.html.twig', [
            'festivalEdition' => $festivalEdition,
            'editionArtists' => $editionArtists,
        ]);
    }

    #[Route('/new', name: 'edition_artist_new', requirements: ['editionId' => '\d+'], methods: ['GET', 'POST'])]
    public function new(
        int $editionId,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $request->getSession();
        $session->start();

        $festivalEdition = $entityManager->getRepository(FestivalEdition::class)->find($editionId);
        if (!$festivalEdition) {
            $this->addFlash('error', 'Festival edition not found.');
            return $this->redirectToRoute('festival_edition_index', ['festival' => $request->query->get('festival')]);
        }

        $editionArtist = new EditionArtist();
        $editionArtist->setEdition($festivalEdition);
        $form = $this->createForm(EditionArtistTypeForm::class, $editionArtist);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if ($editionArtist->getStartTime() < $editionArtist->getEndTime()) {

                    $entityManager->persist($editionArtist);
                    $entityManager->flush();

                    $this->addFlash('success', 'Artist added to edition successfully!');
                    $request->getSession()->getFlashBag()->clear();

                    return $this->redirectToRoute('edition_artist_index', ['editionId' => $editionId]);

                } else {
                    $this->addFlash('error', 'End time must be after start time !');
                }

            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }
        return $this->render('edition_artist/new.html.twig', [
            'festivalEdition' => $festivalEdition,
            'editionArtistForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'edition_artist_delete', requirements: ['id' => '\d+', 'editionId' => '\d+'], methods: ['POST'])]
    public function delete(
        int $editionId,
        EditionArtist $editionArtist,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $session = $request->getSession();
        $session->start();

        $festivalEdition = $entityManager->getRepository(FestivalEdition::class)->find($editionId);
        if (!$festivalEdition) {
            $this->addFlash('error', 'Festival edition not found.');
            return $this->redirectToRoute('festival_edition_index', ['festival' => $request->query->get('festival')]);
        }

        if (!$this->isCsrfTokenValid('delete_edition_artist_' . $editionArtist->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token!');
            return $this->redirectToRoute('edition_artist_index', ['editionId' => $editionId]);
        }

        $entityManager->remove($editionArtist);
        $entityManager->flush();

        $this->addFlash('success', 'Artist removed from edition successfully!');
        $request->getSession()->getFlashBag()->clear();
        
        return $this->redirectToRoute('edition_artist_index', ['editionId' => $editionId]);
    }
}
