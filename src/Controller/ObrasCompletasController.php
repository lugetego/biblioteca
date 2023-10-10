<?php

namespace App\Controller;

use App\Entity\ObrasCompletas;
use App\Form\ObrasCompletasType;
use App\Repository\ObrasCompletasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/obras-completas")
 */
class ObrasCompletasController extends AbstractController
{
    /**
     * @Route("/", name="app_obras_completas_index", methods={"GET"})
     */
    public function index(ObrasCompletasRepository $obrasCompletasRepository): Response
    {
        return $this->render('obras_completas/index.html.twig', [
            'obras_completas' => $obrasCompletasRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_obras_completas_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ObrasCompletasRepository $obrasCompletasRepository): Response
    {
        $obrasCompleta = new ObrasCompletas();
        $form = $this->createForm(ObrasCompletasType::class, $obrasCompleta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $obrasCompletasRepository->add($obrasCompleta, true);

            return $this->redirectToRoute('app_obras_completas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('obras_completas/new.html.twig', [
            'obras_completa' => $obrasCompleta,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_obras_completas_show", methods={"GET"})
     */
    public function show(ObrasCompletas $obrasCompleta): Response
    {
        return $this->render('obras_completas/show.html.twig', [
            'obras_completa' => $obrasCompleta,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_obras_completas_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ObrasCompletas $obrasCompleta, ObrasCompletasRepository $obrasCompletasRepository): Response
    {
        $form = $this->createForm(ObrasCompletasType::class, $obrasCompleta);
        $form->handleRequest($request);

       /*   foreach ($obrasCompletasRepository->findAll() as $obrasCompleta) {
            $obrasCompleta->setSlug($obrasCompleta->getTitulo());
            $obrasCompletasRepository->add($obrasCompleta, true);
        }*/

        if ($form->isSubmitted() && $form->isValid()) {
            $obrasCompletasRepository->add($obrasCompleta, true);

            return $this->redirectToRoute('app_obras_completas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('obras_completas/edit.html.twig', [
            'obras_completa' => $obrasCompleta,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_obras_completas_delete", methods={"POST"})
     */
    public function delete(Request $request, ObrasCompletas $obrasCompleta, ObrasCompletasRepository $obrasCompletasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$obrasCompleta->getId(), $request->request->get('_token'))) {
            $obrasCompletasRepository->remove($obrasCompleta, true);
        }

        return $this->redirectToRoute('app_obras_completas_index', [], Response::HTTP_SEE_OTHER);
    }
}
