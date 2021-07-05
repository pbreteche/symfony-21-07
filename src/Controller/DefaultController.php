<?php

namespace App\Controller;

use App\Model\ArticleProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", methods="GET")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("")
     */
    public function index(ArticleProvider $provider): Response
    {
        return $this->render('default/index.html.twig', [
            'ids' => $provider->list(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function show(ArticleProvider $provider, int $id): Response
    {
        return $this->render('default/show.html.twig', [
            'article' => $provider->get($id),
        ]);
    }
}
