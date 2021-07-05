<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    public function named(string $name): Response
    {
        return new Response('Bonjour '.$name);
    }

    public function numbered(int $id, Request $request): Response
    {
        $version = $request->query->get('version', 'latest'); // $_GET['version']
        if ('POST' === $request->getMethod()) {
            $postData = $request->request->get('data'); //$_POST['data'];
            $request->getSession()->set('post', $postData); // $_SESSION
        }

        return new Response($id);
    }
}
