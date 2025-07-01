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
class FestivalController1 extends AbstractController
{
    // ✅ LIST with pagination: GET /api/festivals?page=1&limit=10
    #[Route('', name: 'festival_index', methods: ['GET'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        // Get pagination params (default: page=1, limit=10)
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = min(100, max(1, (int)$request->query->get('limit', 10)));
        $offset = ($page - 1) * $limit;

        // Build a paginated query
        $qb = $em->createQueryBuilder()
            ->select('f')
            ->from(Festival::class, 'f')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $paginator = new Paginator($qb, fetchJoinCollection: true);
        $festivals = iterator_to_array($paginator);

        // Return JSON with pagination info
        return $this->json([
            'data' => $festivals,
            'pagination' => [
                'current_page' => $page,
                'limit' => $limit,
                'total_items' => count($paginator),
                'total_pages' => ceil(count($paginator) / $limit)
            ]
        ]);
    }

    // ✅ SHOW: GET /api/festivals?id=1
    #[Route('/show', name: 'festival_show', methods: ['GET'])]
    public function show(Request $request, FestivalRepository $repository): Response
    {
        $id = (int)$request->query->get('id');

        if (!$id) {
            return $this->json(['error' => 'Missing ID'], Response::HTTP_BAD_REQUEST);
        }

        $festival = $repository->find($id);

        if (!$festival) {
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($festival);
    }

    // ✅ CREATE: POST /api/festivals
    #[Route('', name: 'festival_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // Read data from JSON body
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name']) || !isset($data['location'])) {
            return $this->json(['error' => 'Missing required fields'], Response::HTTP_BAD_REQUEST);
        }

        $festival = new Festival();
        $festival->setName($data['name']);
        $festival->setLocation($data['location']);

        $em->persist($festival);
        $em->flush();

        return $this->json(['message' => 'Festival created', 'id' => $festival->getId()], Response::HTTP_CREATED);
    }

    // ✅ UPDATE: PUT /api/festivals?id=1
    #[Route('', name: 'festival_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $em, FestivalRepository $repository): Response
    {
        $id = (int)$request->query->get('id');

        if (!$id) {
            return $this->json(['error' => 'Missing ID'], Response::HTTP_BAD_REQUEST);
        }

        $festival = $repository->find($id);

        if (!$festival) {
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $festival->setName($data['name']);
        }

        if (isset($data['location'])) {
            $festival->setLocation($data['location']);
        }

        $em->flush();

        return $this->json(['message' => 'Festival updated']);
    }

    // ✅ DELETE: DELETE /api/festivals?id=1
    #[Route('', name: 'festival_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em, FestivalRepository $repository): Response
    {
        $id = (int)$request->query->get('id');

        if (!$id) {
            return $this->json(['error' => 'Missing ID'], Response::HTTP_BAD_REQUEST);
        }

        $festival = $repository->find($id);

        if (!$festival) {
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($festival);
        $em->flush();

        return $this->json(['message' => 'Festival deleted']);
    }
}
