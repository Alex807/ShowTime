<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Form\FestivalTypeForm;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/festivals')]  //base route for entire controller
final class FestivalController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('/', name: 'festival_index', methods: ['GET'])] //restful paths = routes respect some naming rules
    public function index(
        FestivalRepository $festivalRepository,
        PaginatorInterface $paginator,
        Request $request ): Response
    {
        // Add cache headers for better performance(only used for pagination buttons jumps)
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $query = $festivalRepository->createQueryBuilder('f')->getQuery();

        $festivals = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO), //page_no where to start show festivals
            self::ITEMS_PER_PAGE
        );

        return $this->render('festival/profile.html.twig', [
            'festivals' => $festivals,
        ], $response);
    }

    #[Route('/new', name: 'festival_new', methods: ['GET', 'POST'])] //we need GET for collecting input data from user and POST to actual update
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $festival = new Festival();
        $form = $this->createForm(FestivalTypeForm::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $festival->setUpdatedAt(new \DateTime()); // always auto-set fields with NOW value

            $entityManager->persist($festival);
            $entityManager->flush();

            $this->addFlash('success', 'Festival created successfully!');
            return $this->redirectToRoute('festival_index');
        }

        return $this->render('festival/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'festival_show', requirements: ['id' => '\d+'], methods: ['GET'])]
     public function show(Festival $festival): Response //we display only 1 festival
    {
        return $this->render('festival_edition/list.html.twig', [ //direct redirecting to the festival_edition
            'festival' => $festival,
            'editions' => $festival->getFestivalEditions()
        ]);
    }

    #[Route('/{id}/edit', name: 'festival_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Festival $festival, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FestivalTypeForm::class, $festival);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $festival->setUpdatedAt(new \DateTime()); // always auto-set fields with NOW value
            $entityManager->flush();

            $this->addFlash('success', 'Festival updated successfully!');
            $request->getSession()->getFlashBag()->clear(); //this line prevent the propagation of flash msj to redirected page after success

            // Handle Turbo request differently
            if ($request->headers->get('Turbo-Frame')) {
                $response = new Response(null, Response::HTTP_SEE_OTHER);
                $response->headers->set('Location', $this->generateUrl('festival_index'));
                return $response;
            }

            return $this->redirectToRoute('festival_index');
        }

        return $this->render('festival/edit.html.twig', [
            'festival' => $festival,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'festival_delete', requirements: ['id' => '\d+'], methods: ['POST'])] //name param is used for redirect cases only
    public function delete(Festival $festival, Request $request, EntityManagerInterface $entityManager): Response //DELETE method need to avoid bcs
                                        // not all web browsers support it(use POST instead)
    { //we use for routes <requirements> to prevent conflicting routes misinterpreted by Symfony

        if (!$this->isCsrfTokenValid('delete_festival_' . $festival->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token!');
            return $this->redirectToRoute('festival_index');
        }

        $entityManager->remove($festival);
        $entityManager->flush();

        $this->addFlash('success', 'Festival deleted successfully!');
        return $this->redirectToRoute('festival_index');
    }
}
