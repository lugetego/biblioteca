<?php

namespace App\Controller;

use App\Entity\Alerta;
use App\Form\AlertaType;
use App\Repository\AlertaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/alerta")
 */
class AlertaController extends AbstractController
{
    /**
     * @Route("/", name="app_alerta_index", methods={"GET"})
     */
    public function index(AlertaRepository $alertaRepository): Response
    {
        return $this->render('alerta/index.html.twig', [
            'alertas' => $alertaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/ultima", name="app_alerta_ultima", methods={"GET"})
     */
    public function ultima(AlertaRepository $alertaRepository): Response
    {
        return $this->render('alerta/ultima.html.twig', [
            'alertas' => $alertaRepository->findLastAlerta()
        ]);
    }

    /**
     * @Route("/new", name="app_alerta_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AlertaRepository $alertaRepository): Response
    {
        $alertum = new Alerta();
        $form = $this->createForm(AlertaType::class, $alertum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alertaRepository->add($alertum, true);

            return $this->redirectToRoute('app_alerta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alerta/new.html.twig', [
            'alertum' => $alertum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="app_alerta_show", methods={"GET"})
     */
    public function show(Alerta $alertum): Response
    {
        return $this->render('alerta/show.html.twig', [
            'alertum' => $alertum,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="app_alerta_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Alerta $alertum, AlertaRepository $alertaRepository): Response
    {
        $form = $this->createForm(AlertaType::class, $alertum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $alertaRepository->add($alertum, true);

            return $this->redirectToRoute('app_alerta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('alerta/edit.html.twig', [
            'alertum' => $alertum,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="app_alerta_delete", methods={"POST"})
     */
    public function delete(Request $request, Alerta $alertum, AlertaRepository $alertaRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$alertum->getSlug(), $request->request->get('_token'))) {
            $alertaRepository->remove($alertum, true);
        }

        return $this->redirectToRoute('app_alerta_index', [], Response::HTTP_SEE_OTHER);
    }
}
