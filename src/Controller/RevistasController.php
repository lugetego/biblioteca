<?php

namespace App\Controller;

use App\Entity\Revistas;
use App\Form\RevistasType;
use App\Repository\RevistasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/revistas")
 */
class RevistasController extends AbstractController
{
    /**
     * @Route("/", name="app_revistas_index", methods={"GET"})
     */
    public function index(RevistasRepository $revistasRepository): Response
    {

        return $this->render('revistas/index.html.twig', [
            'revistas' => $revistasRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_revistas_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RevistasRepository $revistasRepository): Response
    {
        $revista = new Revistas();
        $form = $this->createForm(RevistasType::class, $revista);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $revistasRepository->add($revista, true);

            return $this->redirectToRoute('app_revistas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('revistas/new.html.twig', [
            'revista' => $revista,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="app_revistas_show", methods={"GET"})
     */
    public function show(Revistas $revista): Response
    {
        return $this->render('revistas/show.html.twig', [
            'revista' => $revista,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="app_revistas_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Revistas $revista, RevistasRepository $revistasRepository): Response
    {
        $form = $this->createForm(RevistasType::class, $revista);
        $form->handleRequest($request);

      /*  foreach ($revistasRepository->findAll() as $revista) {
            $revista->setSlug($revista->getNombre());
            $revistasRepository->add($revista, true);
        }*/

        if ($form->isSubmitted() && $form->isValid()) {

            $revistasRepository->add($revista, true);

            return $this->redirectToRoute('app_revistas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('revistas/edit.html.twig', [
            'revista' => $revista,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_revistas_delete", methods={"POST"})
     */
    public function delete(Request $request, Revistas $revista, RevistasRepository $revistasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$revista->getId(), $request->request->get('_token'))) {
            $revistasRepository->remove($revista, true);
        }

        return $this->redirectToRoute('app_revistas_index', [], Response::HTTP_SEE_OTHER);
    }
}
