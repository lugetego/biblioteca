<?php

namespace App\Controller;

use App\Entity\Libro;
use App\Form\LibroType;
use App\Repository\AlertaRepository;
use App\Repository\LibroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/libro")
 */
class LibroController extends AbstractController
{
    /**
     * @Route("/", name="app_libro_index", methods={"GET"})
     */
    public function index(LibroRepository $libroRepository): Response
    {
        return $this->render('libro/index.html.twig', [
            'libros' => $libroRepository->findAll(),

        ]);
    }

    /**
     * @Route("/alerta/{alerta}", name="app_libro_alerta", methods={"GET"})
     */
    public function alerta(AlertaRepository $alertaRepository, $alerta): Response
    {
        // Find the alerta entity

        $alerta = $alertaRepository->find($alerta);

        if (!$alerta) {
            throw $this->createNotFoundException('No author found for id ' . $alerta);
        }

        // Access the associated books
        $libros = $alerta->getLibro();
        return $this->render('libro/alerta.html.twig', [
            'libros' => $libros,
            'alerta' => $alerta,

        ]);
    }

    /**
     * @Route("/new", name="app_libro_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LibroRepository $libroRepository): Response
    {
        $libro = new Libro();
        $form = $this->createForm(LibroType::class, $libro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $libroRepository->add($libro, true);

            return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('libro/new.html.twig', [
            'libro' => $libro,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="app_libro_show", methods={"GET"})
     */
    public function show(Libro $libro): Response
    {
        return $this->render('libro/show.html.twig', [
            'libro' => $libro,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="app_libro_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Libro $libro, LibroRepository $libroRepository): Response
    {
        $form = $this->createForm(LibroType::class, $libro);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $libroRepository->add($libro, true);

            return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('libro/edit.html.twig', [
            'libro' => $libro,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="app_libro_delete", methods={"POST"})
     */
    public function delete(Request $request, Libro $libro, LibroRepository $libroRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$libro->getId(), $request->request->get('_token'))) {
            $libroRepository->remove($libro, true);
        }

        return $this->redirectToRoute('app_libro_index', [], Response::HTTP_SEE_OTHER);
    }
}
