<?php

namespace App\Controller;

use App\Entity\Sitios;
use App\Form\SitiosType;
use App\Repository\SitiosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sitios")
 */
class SitiosController extends AbstractController
{
    /**
     * @Route("/", name="app_sitios_index", methods={"GET"})
     */
    public function index(SitiosRepository $sitiosRepository): Response
    {
        return $this->render('sitios/index.html.twig', [
            'sitios' => $sitiosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_sitios_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SitiosRepository $sitiosRepository): Response
    {
        $sitio = new Sitios();
        $form = $this->createForm(SitiosType::class, $sitio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sitiosRepository->add($sitio, true);

            return $this->redirectToRoute('app_sitios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sitios/new.html.twig', [
            'sitio' => $sitio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sitios_show", methods={"GET"})
     */
    public function show(Sitios $sitio): Response
    {
        return $this->render('sitios/show.html.twig', [
            'sitio' => $sitio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sitios_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sitios $sitio, SitiosRepository $sitiosRepository): Response
    {
        $form = $this->createForm(SitiosType::class, $sitio);
        $form->handleRequest($request);

       /* foreach ($sitiosRepository->findAll() as $sitio) {
            $sitio->setSlug($sitio->getNombre());
            $sitiosRepository->add($sitio, true);
        }*/

        if ($form->isSubmitted() && $form->isValid()) {
            $sitiosRepository->add($sitio, true);

            return $this->redirectToRoute('app_sitios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sitios/edit.html.twig', [
            'sitio' => $sitio,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sitios_delete", methods={"POST"})
     */
    public function delete(Request $request, Sitios $sitio, SitiosRepository $sitiosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sitio->getId(), $request->request->get('_token'))) {
            $sitiosRepository->remove($sitio, true);
        }

        return $this->redirectToRoute('app_sitios_index', [], Response::HTTP_SEE_OTHER);
    }
}
