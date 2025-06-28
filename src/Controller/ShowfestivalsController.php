<?php

namespace App\Controller;

use App\Entity\Festival;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\FestivalRepository;
use Symfony\Component\Routing\Attribute\Route;

final class ShowfestivalsController extends AbstractController
{
    #[Route('/showfestivals', name: 'app_showfestivals', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $festivals = $entityManager->getRepository(Festival::class)->findAll();
       // dd($festivals); //break point

        return $this->render('showfestivals/index.html.twig', [
            'controller_name' => 'ShowfestivalsController',
            'festivals' => $festivals
        ]);
    }
}
