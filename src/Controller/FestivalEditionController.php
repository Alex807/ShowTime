<?php
namespace App\Controller;

use App\Entity\Festival;
use App\Entity\FestivalEdition;
use App\Form\FestivalEditionTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/festival/edition')]
class FestivalEditionController extends AbstractController
{
    #[Route('/{festival}/list', name: 'festival_edition_list', requirements: ['festival' => '\d+'], methods: ['GET', 'POST'])]    public function list(Festival $festival): Response
    {
        return $this->render('festival_edition/list.html.twig', [
            'festival' => $festival,
            'editions' => $festival->getFestivalEditions()
        ]);
    }

    #[Route('/{festival}/new', name: 'festival_edition_new', requirements: ['festival' => '\d+'], methods: ['GET', 'POST'])]
    public function new(Request $request, Festival $festival, EntityManagerInterface $entityManager): Response
    {
        $edition = new FestivalEdition();
        $edition->setFestival($festival);

        $form = $this->createForm(FestivalEditionTypeForm::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edition);
            $entityManager->flush();

            $this->addFlash('success', 'Edition created successfully!');
            return $this->redirectToRoute('festival_edition_list', ['festival' => $festival->getId()]);
        }

        return $this->render('festival_edition/new.html.twig', [
            'festival' => $festival,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'festival_edition_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, FestivalEdition $edition, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(FestivalEditionTypeForm::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush();

                $this->addFlash('success', 'Edition updated successfully!');
                return $this->redirectToRoute('festival_edition_list', ['festival' => $edition->getFestival()->getId()]);

            } else {
                // Handle constraints from FORM
                $errors = $form->getErrors(true);
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }
        return $this->render('festival_edition/edit.html.twig', [ //create the missing twig files for edition
            'edition' => $edition,
            'festival' => $edition->getFestival(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/delete', name: 'festival_edition_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(Request $request, FestivalEdition $edition, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$edition->getId(), $request->request->get('_token'))) {
            $festivalId = $edition->getFestival()->getId();
            $entityManager->remove($edition);
            $entityManager->flush();

            $this->addFlash('success', 'Edition deleted successfully!');
            return $this->redirectToRoute('festival_edition_list', ['festival' => $festivalId]);
        }

        return $this->redirectToRoute('festival_edition_list', ['festival' => $edition->getFestival()->getId()]);
    }
}
