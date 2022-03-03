<?php

namespace App\Controller;

use App\Entity\Cost;
use App\Form\CostType;
use App\Repository\CostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/cost')]
class CostController extends AbstractController
{
    #[Route('/', name: 'app_cost_index', methods: ['GET'])]
    public function index(CostRepository $costRepository): Response
    {
        return $this->render('cost/index.html.twig', [
            'costs' => $costRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_cost_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CostRepository $costRepository): Response
    {
        $cost = new Cost();
        $form = $this->createForm(CostType::class, $cost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $costRepository->add($cost);
            return $this->redirectToRoute('app_cost_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cost/new.html.twig', [
            'cost' => $cost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cost_show', methods: ['GET'])]
    public function show(Cost $cost): Response
    {
        return $this->render('cost/show.html.twig', [
            'cost' => $cost,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cost_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cost $cost, CostRepository $costRepository): Response
    {
        $form = $this->createForm(CostType::class, $cost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $costRepository->add($cost);
            return $this->redirectToRoute('app_cost_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('cost/edit.html.twig', [
            'cost' => $cost,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cost_delete', methods: ['POST'])]
    public function delete(Request $request, Cost $cost, CostRepository $costRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cost->getId(), $request->request->get('_token'))) {
            $costRepository->remove($cost);
        }

        return $this->redirectToRoute('app_cost_index', [], Response::HTTP_SEE_OTHER);
    }
}
