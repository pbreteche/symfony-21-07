<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\ArticleRepository;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/author", methods="GET")
 */
class AuthorController extends AbstractController
{
    private const ARTICLES_LIMIT = 10;

    /**
     * @Route("/")
     */
    public function index(AuthorRepository $repository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $repository->findAll(),
        ]);
    }

    /**
     * @Route(
     *     "/{id}/{page}",
     *     requirements={"id": "\d+", "page": "\d+"},
     *     defaults={"page": 1}
     * )
     */
    public function show(
        Author $author,
        int $page,
        ArticleRepository $articleRepository
    ): Response {
        $articles = $articleRepository->findBy(
            ['writtenBy' => $author],
            ['publishedAt' => 'DESC'],
            self::ARTICLES_LIMIT,
            ($page - 1) * self::ARTICLES_LIMIT
        );

        return $this->render('author/show.html.twig', [
            'author' => $author,
            'articles' => $articles,
        ]);
    }
}