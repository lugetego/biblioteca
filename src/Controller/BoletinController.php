<?php

namespace App\Controller;

use App\Entity\Boletin;
use App\Form\BoletinType;
use App\Repository\BoletinRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/boletin")
 */
class BoletinController extends AbstractController
{
    /**
     * @Route("/", name="app_boletin_index", methods={"GET"})
     */
    public function index(BoletinRepository $boletinRepository): Response
    {
        return $this->render('boletin/index.html.twig', [
            'boletins' => $boletinRepository->findAll(),
        ]);
    }

    /**
     * @Route("/consulta/{anio}", name="app_boletin_consulta", methods={"GET"})
     */
    public function consulta(BoletinRepository $boletinRepository, $anio): Response
    {
        return $this->render('boletin/consulta.html.twig', [
            'boletins' => $boletinRepository->findBy(array('anio' => $anio), array('anio' => 'ASC') // Order by 'anio' in ascending order
            ),
            'anio' => $anio,
        ]);
    }

    /**
     * @Route("/new", name="app_boletin_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BoletinRepository $boletinRepository): Response
    {
        $boletin = new Boletin();
        $form = $this->createForm(BoletinType::class, $boletin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boletinRepository->add($boletin, true);

            return $this->redirectToRoute('app_boletin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boletin/new.html.twig', [
            'boletin' => $boletin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{slug}", name="app_boletin_show", methods={"GET"})
     */
    public function show(Boletin $boletin): Response
    {

        return $this->render('boletin/show.html.twig', [
            'boletin' => $boletin,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="app_boletin_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Boletin $boletin, BoletinRepository $boletinRepository): Response
    {
        $form = $this->createForm(BoletinType::class, $boletin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $boletin->setModified(new \DateTime());

            $boletinRepository->add($boletin, true);

            return $this->redirectToRoute('app_boletin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('boletin/edit.html.twig', [
            'boletin' => $boletin,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_boletin_delete", methods={"POST"})
     */
    public function delete(Request $request, Boletin $boletin, BoletinRepository $boletinRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$boletin->getId(), $request->request->get('_token'))) {
            $boletinRepository->remove($boletin, true);
        }

        return $this->redirectToRoute('app_boletin_index', [], Response::HTTP_SEE_OTHER);
    }
}
