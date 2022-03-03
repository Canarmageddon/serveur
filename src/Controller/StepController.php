<?php

namespace App\Controller;

use App\Entity\Step;
use App\Form\StepType;
use App\Repository\StepRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/step')]
class StepController extends AbstractController
{
    #[Route('/', name: 'app_step_index', methods: ['GET'])]
    public function index(StepRepository $stepRepository): Response
    {
        return $this->render('step/index.html.twig', [
            'steps' => $stepRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_step_new', methods: ['GET', 'POST'])]
    public function new(Request $request, StepRepository $stepRepository): Response
    {
        $step = new Step();
        $form = $this->createForm(StepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stepRepository->add($step);
            return $this->redirectToRoute('app_step_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('step/new.html.twig', [
            'step' => $step,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_step_show', methods: ['GET'])]
    public function show(Step $step): Response
    {
        return $this->render('step/show.html.twig', [
            'step' => $step,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_step_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Step $step, StepRepository $stepRepository): Response
    {
        $form = $this->createForm(StepType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stepRepository->add($step);
            return $this->redirectToRoute('app_step_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('step/edit.html.twig', [
            'step' => $step,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_step_delete', methods: ['POST'])]
    public function delete(Request $request, Step $step, StepRepository $stepRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$step->getId(), $request->request->get('_token'))) {
            $stepRepository->remove($step);
        }

        return $this->redirectToRoute('app_step_index', [], Response::HTTP_SEE_OTHER);
    }
}
