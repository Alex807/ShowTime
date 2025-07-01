<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TicketTypeController extends AbstractController
{
    #[Route('/tickets', name: 'app_ticket_type')]
    public function index(): Response
    {
        return $this->render('ticket_type/index.html.twig', [
            'controller_name' => 'TicketTypeController',
        ]);
    }
}
