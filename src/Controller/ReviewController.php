<?php

namespace App\Controller;

use App\Entity\EditionReview;
use App\Entity\Festival;
use App\Entity\FestivalEdition;
use App\Form\EditionReviewTypeForm;
use App\Repository\FestivalRepository;
use App\Repository\EditionReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/reviews')]
#[IsGranted('ROLE_USER')]
final class ReviewController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'user_reviews', methods: ['GET'])]
    public function userReviews(EditionReviewRepository $reviewRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $user = $this->getUser();
        $query = $reviewRepository->createQueryBuilder('r')
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->orderBy('r.posted_at', 'DESC')
            ->getQuery();

        $reviews = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('review/index.html.twig', [
            'reviews' => $reviews,
        ], $response);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/all', name: 'review_index', methods: ['GET'])]
    public function index(EditionReviewRepository $reviewRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $query = $reviewRepository->createQueryBuilder('r')
            ->orderBy('r.posted_at', 'DESC')
            ->getQuery();

        $reviews = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('review/admin_index.html.twig', [
            'reviews' => $reviews,
        ], $response);
    }

    #[Route('/new/{editionId}', name: 'review_new', requirements: ['editionId' => '\d+'], methods: ['GET', 'POST'])]
    public function new(int $editionId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $session->start();

        $edition = $entityManager->getRepository(FestivalEdition::class)->find($editionId);
        if (!$edition) {
            $this->addFlash('error', 'Festival edition not found.');
            return $this->redirectToRoute('festival_search');
        }

        $review = new EditionReview();
        $review->setEdition($edition);
        $review->setUser($this->getUser());
        $review->setPostedAt(new \DateTime());

        $form = $this->createForm(EditionReviewTypeForm::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($review);
                $entityManager->flush();

                $this->addFlash('success', 'Review submitted successfully!');
                $request->getSession()->getFlashBag()->clear();

                return $this->redirectToRoute('user_reviews');
            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('review/new.html.twig', [
            'form' => $form->createView(),
            'edition' => $edition,
        ]);
    }
}
