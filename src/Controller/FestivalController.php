<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken; //need them to double ask when you want to delete something

#[Route('/festival')] // base route for this controller (entry point in controller)
final class FestivalController extends AbstractController
{

//    #[Route('/festival', name: 'list_festivals', methods: ['GET'])]
//    public function index(
//        FestivalRepository $festivalRepository,  //* Obiect prin care am acces la datele din baza de date
//        PaginatorInterface $paginator,
//        Request $request
//
//    ): Response {
//        $query = $festivalRepository->createQueryBuilder('f')->getQuery();  //* query-ul pentru selectia datelor
//
//        // Paginate the query
//        $festivals = $paginator->paginate(
//            $query,
//            $request->query->getInt('page', 1), // Mereu la apelul metodei, saltul se face la prima pagina
//            $this->ItemsPerPage
//        ); //* variabila in care salvez datele din baza de date, dar si paginarea cu maxim 10 inregistrari pe pagina
//
//        return $this->render('festival.html.twig', [
//            'festivals' => $festivals,
//        ]);
//    }

    #[Route('/festival', name: 'app_showfestivals', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $festivals = $entityManager->getRepository(Festival::class)->findAll();
       // dd($festivals); //break point (prints what contains the variable)

        return $this->render('festival/index.html.twig', [
            'controller_name' => 'FestivalController',
            'festivals' => $festivals
        ]);
    }

    #[Route('/delete/{id}', name: 'app_delete_festivals', methods: ['POST'])] //name param is used for redirect cases only
    public function deleteById(EntityManagerInterface $entityManager, int $id): Response //DELETE method need to avoid bcs not all web browsers support it(use POST instead)
    {
        $festival = $entityManager->getRepository(Festival::class)->find($id);
        // dd($festival); //break point (prints what contains the variable)

        if (!$festival) {
            $this->addFlash('error', 'Festival not found.');
            //return $this->redirectToRoute('app_showfestivals');
            return new Response('Festival not found.', Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($festival);
        $entityManager->flush();

        //return Response('Festival deleted successfully.', Response::HTTP_OK);
        $this->addFlash('success', 'Festival deleted successfully.');
        return $this->redirectToRoute('app_showfestivals');
    }

//    #[Route('/delete/{id}', name: 'app_delete_festival', methods: ['POST'])]
//    public function deleteById(Request $request, EntityManagerInterface $entityManager, int $id): Response
//    {
//        $festival = $entityManager->getRepository(Festival::class)->find($id);
//
//        if (!$festival) {
//            $this->addFlash('error', 'Festival not found.');
//            return $this->redirectToRoute('app_showfestivals');
//        }
//
//        $submittedToken = $request->request->get('_token');
//        if (!$this->isCsrfTokenValid('delete' . $id, $submittedToken)) {
//            $this->addFlash('error', 'Invalid CSRF token.');
//            return $this->redirectToRoute('app_showfestivals');
//        }
//
//        $entityManager->remove($festival);
//        $entityManager->flush();
//
//        $this->addFlash('success', 'Festival deleted successfully.');
//        return $this->redirectToRoute('app_showfestivals');
//    }

    #[Route('/festival/{id}', name: 'app_festival_details', methods: ['GET'])]
    public function showOne(EntityManagerInterface $entityManager, int $id): Response
    {
        $festival = $entityManager->getRepository(Festival::class)->find($id);

        if (!$festival) {
            throw $this->createNotFoundException("Festival not found.");
        }

        return $this->render('festival/festival_details.html.twig', [
            'festival' => $festival,
        ]);
    }
}
