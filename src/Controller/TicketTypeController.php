<?php
//
//namespace App\Controller;
//
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Attribute\Route;
//
//final class TicketTypeController extends AbstractController
//{
//    #[Route('/tickets', name: 'app_ticket_type')]
//    public function index(): Response
//    {
//        return $this->render('ticket_type/index.html.twig', [
//            'controller_name' => 'TicketTypeController',
//        ]);
//    }
//}
//


namespace App\Controller;

use App\Entity\Festival;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/festivals')] // All routes in this controller will start with /api/festivals
class TicketTypeController extends AbstractController
{

}
