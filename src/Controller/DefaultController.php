<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * Access to the root of the project redirects to / api.
     * 
     * @Route("/", name="app_home")
     */
    public function home(): RedirectResponse
    {
        return new RedirectResponse('/api');
    }

}
