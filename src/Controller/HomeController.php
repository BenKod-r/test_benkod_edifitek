<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->isGranted("ROLE_MENTOR") ?
           $this->redirectToRoute('app_mentor_index') :
            $this->redirectToRoute('app_developer_index');
    }

}
