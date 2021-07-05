<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends AbstractController
{

    public function index(): Response
    {
        $response = (new Response())
            ->setContent('Bonjour tout le monde')
        ;
        $response->headers->set('Content-Type', 'text/plain');

        return $response;
    }
}
