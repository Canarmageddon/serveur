<?php

namespace App\Controller;

use App\Entity\Itinerary;
use App\Form\ItineraryType;
use App\Repository\ItineraryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/itinerary')]
class ItineraryController extends AbstractController
{
    #[Route('/', name: 'app_itinerary_index', methods: ['GET'])]
    public function index(ItineraryRepository $itineraryRepository): Response
    {
        return $this->render('itinerary/index.html.twig', [
            'itineraries' => $itineraryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_itinerary_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ItineraryRepository $itineraryRepository): Response
    {
        $itinerary = new Itinerary();
        $form = $this->createForm(ItineraryType::class, $itinerary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itineraryRepository->add($itinerary);
            return $this->redirectToRoute('app_itinerary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('itinerary/new.html.twig', [
            'itinerary' => $itinerary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_itinerary_show', methods: ['GET'])]
    public function show(Itinerary $itinerary): Response
    {
        return $this->render('itinerary/show.html.twig', [
            'itinerary' => $itinerary,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_itinerary_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Itinerary $itinerary, ItineraryRepository $itineraryRepository): Response
    {
        $form = $this->createForm(ItineraryType::class, $itinerary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itineraryRepository->add($itinerary);
            return $this->redirectToRoute('app_itinerary_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('itinerary/edit.html.twig', [
            'itinerary' => $itinerary,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_itinerary_delete', methods: ['POST'])]
    public function delete(Request $request, Itinerary $itinerary, ItineraryRepository $itineraryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$itinerary->getId(), $request->request->get('_token'))) {
            $itineraryRepository->remove($itinerary);
        }

        return $this->redirectToRoute('app_itinerary_index', [], Response::HTTP_SEE_OTHER);
    }
}
