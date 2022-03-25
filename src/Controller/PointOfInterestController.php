<?php

namespace App\Controller;

use App\Entity\PointOfInterest;
use App\Form\PointOfInterestType;
use App\Repository\PointOfInterestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/point_of_interest')]
class PointOfInterestController extends AbstractController
{
    #[Route('/', name: 'app_point_of_interest_index', methods: ['GET'])]
    public function index(PointOfInterestRepository $pointOfInterestRepository): Response
    {
        return $this->render('point_of_interest/index.html.twig', [
            'point_of_interests' => $pointOfInterestRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_point_of_interest_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PointOfInterestRepository $pointOfInterestRepository): Response
    {
        $pointOfInterest = new PointOfInterest();
        $form = $this->createForm(PointOfInterestType::class, $pointOfInterest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pointOfInterestRepository->add($pointOfInterest);
            return $this->redirectToRoute('app_point_of_interest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('point_of_interest/new.html.twig', [
            'point_of_interest' => $pointOfInterest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_point_of_interest_show', methods: ['GET'])]
    public function show(PointOfInterest $pointOfInterest): Response
    {
        return $this->render('point_of_interest/show.html.twig', [
            'point_of_interest' => $pointOfInterest,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_point_of_interest_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PointOfInterest $pointOfInterest, PointOfInterestRepository $pointOfInterestRepository): Response
    {
        $form = $this->createForm(PointOfInterestType::class, $pointOfInterest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pointOfInterestRepository->add($pointOfInterest);
            return $this->redirectToRoute('app_point_of_interest_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('point_of_interest/edit.html.twig', [
            'point_of_interest' => $pointOfInterest,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_point_of_interest_delete', methods: ['POST'])]
    public function delete(Request $request, PointOfInterest $pointOfInterest, PointOfInterestRepository $pointOfInterestRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointOfInterest->getId(), $request->request->get('_token'))) {
            $pointOfInterestRepository->remove($pointOfInterest);
        }

        return $this->redirectToRoute('app_point_of_interest_index', [], Response::HTTP_SEE_OTHER);
    }
}
