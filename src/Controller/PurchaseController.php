<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\Festival;
use App\Entity\FestivalEdition;
use App\Form\PurchaseTypeForm;
use App\Repository\FestivalRepository;
use App\Repository\PurchaseRepository;
use App\Repository\TicketTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/purchases')]
#[IsGranted('ROLE_USER')]
final class PurchaseController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'user_purchases', methods: ['GET'])]
    public function userPurchases(PurchaseRepository $purchaseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $user = $this->getUser();

        if (!$user) {
            throw new AccessDeniedHttpException();
        }

        $query = $purchaseRepository->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user)
            ->orderBy('p.purchase_date', 'DESC')
            ->getQuery();

        $purchases = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('purchase/admin_index.html.twig', [
            'purchases' => $purchases,
        ], $response);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/all', name: 'purchase_index', methods: ['GET'])]
    public function index(PurchaseRepository $purchaseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $query = $purchaseRepository->createQueryBuilder('p')
            ->orderBy('p.purchase_date', 'DESC')
            ->getQuery();

        $purchases = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('purchase/admin_index.html.twig', [
            'purchases' => $purchases,
        ], $response);
    }

    #[Route('/new/{editionId}', name: 'purchase_new', requirements: ['editionId' => '\d+'], methods: ['GET', 'POST'])]
    public function new(int $editionId, Request $request, EntityManagerInterface $entityManager, PurchaseRepository $purchaseRepository, TicketTypeRepository $ticketTypeRepository): Response
    {
        $session = $request->getSession();
        $session->start();

        $edition = $entityManager->getRepository(FestivalEdition::class)->find($editionId);
        if (!$edition) {
            $this->addFlash('error', 'Festival edition not found.');
            return $this->redirectToRoute('festival_search');
        }

        // Check available capacity
        $totalPurchased = $purchaseRepository->createQueryBuilder('p')
            ->select('SUM(p.quantity)')
            ->where('p.edition = :edition')
            ->setParameter('edition', $edition)
            ->getQuery()
            ->getSingleScalarResult() ?? 0;

        if ($totalPurchased >= $edition->getPeopleCapacity()) {
            $this->addFlash('error', 'This festival edition is sold out.');
            return $this->redirectToRoute('festival_search');
        }

        $purchase = new Purchase();
        $purchase->setEdition($edition);
        $purchase->setUser($this->getUser());
        $purchase->setPurchaseDate(new \DateTime());

        $form = $this->createForm(PurchaseTypeForm::class, $purchase, [
            'ticket_types' => $ticketTypeRepository->findAll(), // Fetch all ticket types
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $quantity = $purchase->getQuantity();
                if ($totalPurchased + $quantity > $edition->getPeopleCapacity()) {
                    $this->addFlash('error', 'Not enough tickets available for this quantity.');
                } else {
                    $entityManager->persist($purchase);
                    $entityManager->flush();

                    $this->addFlash('success', 'Purchase completed successfully!');
                    $request->getSession()->getFlashBag()->clear();

                    return $this->redirectToRoute('user_purchases');
                }
            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('purchase/new.html.twig', [
            'form' => $form->createView(),
            'edition' => $edition,
        ]);
    }

    #[Route('/search', name: 'festival_search', methods: ['GET', 'POST'])]
    public function search(Request $request, FestivalRepository $festivalRepository): Response
    {
        $query = $request->query->get('q', '');
        $festival = null;
        $editions = [];

        if ($query) {
            $festivals = $festivalRepository->searchByName($query);
            if (count($festivals) === 1) {
                $festival = $festivals[0];
                $editions = $festival->getFestivalEditions();
            }
        }

        return $this->render('festival/search.html.twig', [
            'query' => $query,
            'festival' => $festival,
            'editions' => $editions,
        ]);
    }
}
