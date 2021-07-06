<?php

namespace App\Controller;

use App\Model\ArticleProvider;
use App\Repository\ArticleRepository;
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
    public function index(ArticleRepository $repository): Response
    {
        return $this->render('default/index.html.twig', [
            'articles' => $repository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function show(ArticleRepository $repository, int $id): Response
    {
        $article = $repository->find($id);

        if (!$article) {
            throw $this->createNotFoundException();
        }

        return $this->render('default/show.html.twig', [
            'article' => $article,
        ]);
    }
}
