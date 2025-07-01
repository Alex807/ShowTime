<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Request;

#[Route('/festivals')] // base route for this controller (entry point in controller)
final class FestivalController extends AbstractController
{
    private const ITEMS_PER_PAGE = 10;
    private const STARTING_PAGE_NO = 1;

    #[Route('', name: 'festival_index', methods: ['GET'])] //restful paths = routes respect some naming rules
    public function index(
        FestivalRepository $festivalRepository,
        PaginatorInterface $paginator,
        Request $request ): Response
    {
        $query = $festivalRepository->createQueryBuilder('f')->getQuery();

        $festivals = $paginator->paginate(
            $query,
            $request->query->getInt('page', self::STARTING_PAGE_NO), //page_no where to start show festivals
            self::ITEMS_PER_PAGE
        );

        return $this->render('festival/index.html.twig', [
            'festivals' => $festivals,
        ]);
    }

    #[Route('/{id}', name: 'festival_show', methods: ['GET'])]
     public function show(Festival $festival): Response //we display only 1 festival
    {
        return $this->render('festival/details.html.twig', [
            'festival' => $festival,
        ]);
    }

    #[Route('/{id}/delete', name: 'festival_delete', methods: ['POST'])] //name param is used for redirect cases only
    public function delete(Festival $festival, Request $request, EntityManagerInterface $entityManager): Response //DELETE method need to avoid bcs not all web browsers support it(use POST instead)
    {
        // dd($festival); //break point (prints what contains the variable)

        if (!$this->isCsrfTokenValid('delete_festival_' . $festival->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token!');
            return $this->redirectToRoute('festival_index');
        }

        $entityManager->remove($festival); //this
        $entityManager->flush();

        $this->addFlash('success', 'Festival deleted successfully!');
        return $this->redirectToRoute('festival_index');
    }
}
