<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/", name="app_site_index")
     */
    public function index(): Response
    {
        return $this->render('site/index.html.twig');
    }

    /**
     * @Route("/nosotros", name="app_site_nosotros")
     */
    public function nosotros(): Response
    {
        return $this->render('site/nosotros.html.twig');
    }

    /**
     * @Route("/recursos", name="app_site_recursos")
     */
    public function recursos(): Response
    {
        return $this->render('site/recursos.html.twig');
    }
}
