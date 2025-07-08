<?php

namespace App\Controller;

use App\Entity\Amenity;
use App\Form\AmenityTypeForm;
use App\Repository\AmenityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/amenities')]
#[IsGranted('ROLE_USER')]
final class AmenityController extends AbstractController
{
    private const ITEMS_PER_PAGE = 9;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'amenity_index', methods: ['GET'])]
    public function index(
        AmenityRepository $amenityRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $amenityRepository->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->getQuery();

        $amenities = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO),
            self::ITEMS_PER_PAGE
        );

        return $this->render('amenity/index.html.twig', [
            'amenities' => $amenities,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/new', name: 'amenity_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $session->start();

        $amenity = new Amenity();
        $form = $this->createForm(AmenityTypeForm::class, $amenity);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->persist($amenity);
                $entityManager->flush();

                $this->addFlash('success', 'Amenity created successfully!');
                $request->getSession()->getFlashBag()->clear(); ////this line prevent the propagation of flash msj to redirected page after success

                return $this->redirectToRoute('amenity_index');

            } else {
                // Handle constraints from FORM
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('amenity/new.html.twig', [
            'amenityForm' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'amenity_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Amenity $amenity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $session->start();

        $form = $this->createForm(AmenityTypeForm::class, $amenity);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush();

                $this->addFlash('success', 'Amenity updated successfully!');
                $request->getSession()->getFlashBag()->clear();

                return $this->redirectToRoute('amenity_index');
            } else {
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('amenity/edit.html.twig', [
            'amenityForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'amenity_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Amenity $amenity): Response
    {
        return $this->render('amenity/details.html.twig', [
            'amenity' => $amenity,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'amenity_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        Amenity $amenity,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        if (!$this->isCsrfTokenValid('delete_amenity_' . $amenity->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token!');
            return $this->redirectToRoute('amenity_index');
        }

        $entityManager->remove($amenity);
        $entityManager->flush();

        $this->addFlash('success', 'Amenity deleted successfully!');
        return $this->redirectToRoute('amenity_index');
    }
}
