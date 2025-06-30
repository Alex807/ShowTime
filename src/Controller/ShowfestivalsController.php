<?php

namespace App\Controller;

use App\Entity\Festival;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

final class ShowfestivalsController extends AbstractController
{
    #[Route('/showfestivals', name: 'app_showfestivals', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $festivals = $entityManager->getRepository(Festival::class)->findAll();
       // dd($festivals); //break point (prints what contains the variable)

        return $this->render('showfestivals/index.html.twig', [
            'controller_name' => 'ShowfestivalsController',
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

        return $this->render('showfestivals/festival_details.html.twig', [
            'festival' => $festival,
        ]);
    }
}
